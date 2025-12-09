<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\DoctorSchedule;
use Illuminate\Support\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\VarDumper\Caster\RedisCaster;

use App\Notifications\AppointmentBooked;
use App\Notifications\NewPatientAppointment;
use App\Notifications\AppointmentConfirmed;
use App\Notifications\AppointmentRescheduled;
use App\Notifications\AppointmentCancelled;
use App\Jobs\SendAppointmentReminderJob;
use App\Models\User;
use Illuminate\Support\Facades\Log;

use function Symfony\Component\Clock\now;

class AdminAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['patient.user', 'doctor.user', 'doctorSchedule']);

        // filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('patient.user', fn($q1) => $q1->where('name', 'like', "%{$q}%"))
                ->orWhereHas('doctor.user', fn($q1) => $q1->where('name', 'like', "%{$q}%"));
        }

        if ($request->filled('date_from')) {
            $query->where('schedule_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('schedule_date', '<=', $request->date_to);
        }

        // if($request->filled('id')) {
        //     $query->where('id', $request->id);
        // }

        $appointments = $query->orderBy('schedule_date', 'desc')
            ->orderBy('schedule_time', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.appointments.index', compact('appointments'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient.user', 'doctor.user', 'doctor.specialty', 'doctorSchedule']);
        $payment = Payment::where('appointment_id', $appointment->id)->first();
        // dd($payment);
        return view('admin.appointments.show', compact('appointment', 'payment'));
    }

    public function create()
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with(['user', 'schedules', 'appointments' => function ($q) {
            $q->where('schedule_date', '>=', now())->where('status', '!=', 'cancelled');
        }])->get();

        return view('admin.appointments.create', compact('patients', 'doctors'));
    }


    public function store(Request $request)
    {

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'doctor_schedule_id' => 'required|exists:doctor_schedules,id',
            // 'schedule_date' => 'required|date',
            'schedule_time' => 'required',
            'type' => 'required|string',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $shedule = DoctorSchedule::findOrFail($request->doctor_schedule_id);
        $day = $shedule->day_of_week;
        $schedule_date = Carbon::now()->next($day)->toDateString();
        $validated['schedule_date'] = $schedule_date;

        $appointment = Appointment::create($validated);

        // $appointment= Appointment::with(['patient.user', 'doctor.user','doctor.specialty', 'doctorSchedule'])->find($appointment->id);
        $appointment->load(['patient.user', 'doctor.user']);
        // SEND NOTIFICATIONS
        $this->sendAppointmentNotifications($appointment);
        // Load for view
        $appointment->load('doctor.specialty', 'doctorSchedule');
        $payment = Payment::where('appointment_id', $appointment->id)->first();

        // return Redirect()->route('admin.appointments.show', compact('appointment', 'payment'))->with('success', 'Appointment created successfully.');
        return view('admin.appointments.show', compact('appointment', 'payment'))
            ->with('success', 'Appointment created successfully! Patient will receive notifications.');;
    }

    public function edit(Appointment $appointment)
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with(['user', 'schedules', 'appointments' => function ($q) use ($appointment) {
            $q->where('schedule_date', '>=', now())
                ->where('status', '!=', 'cancelled')
                ->where('id', '!=', $appointment->id); // exclude current appointment
        }])->get();

        return view('admin.appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'doctor_schedule_id' => 'required|exists:doctor_schedules,id',
            'schedule_time' => 'required',
            'type' => 'required|string',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        // Store old values to detect changes
        $oldStatus = $appointment->status;
        $oldScheduleDate = $appointment->schedule_date;
        $oldScheduleTime = $appointment->schedule_time;

        // calculate schedule_date from schedule's day_of_week
        $schedule = DoctorSchedule::findOrFail($validated['doctor_schedule_id']);
        $day = $schedule->day_of_week;
        $validated['schedule_date'] = Carbon::now()->next($day)->toDateString();

        // check if the selected time slot is booked
        // $exists = Appointment::where('doctor_id', $validated['doctor_id'])
        //     ->where('doctor_shedule_id', $validated['doctor_schedule_id'])
        //     ->where('schedule_date', $validated['schedule_date'])
        //     ->where('schedule_time', $validated['schedule_time'])
        //     ->where('status', '!=', 'cancelled')
        //     ->where('id', '!=', $appointment->id)
        //     ->exists();

        // if($exists) {
        //     return back()->withErrors(['shedule_time' => 'This time slot is already booked.'])->withInput();
        // }

        $appointment->update($validated);
        $appointment->load(['patient.user', 'doctor.user']);


        // SEND NOTIFICATIONS BASED ON CHANGES
        // Status changed to confirmed
        if ($oldStatus !== 'confirmed' && $validated['status'] === 'confirmed') {
            $this->sendConfirmationNotifications($appointment);
        }
        // Date or time changed reschedule
        elseif (
            $oldScheduleDate != $validated['schedule_date'] ||
            $oldScheduleTime != $validated['schedule_time']
        ) {
            $this->sendRescheduleNotifications($appointment);
        }

        // Status changed to cancelled
        elseif ($oldStatus !== 'cancelled' && $validated['status'] === 'cancelled') {
            $this->sendCancellationNotifications($appointment);
        }

        $payment = Payment::where('appointment_id', $appointment->id)->first();

        return redirect()->route('admin.appointments.show', compact('appointment', 'payment'))->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        //  delete related payment
        Payment::where('appointment_id', $appointment->id)->delete();
        // Send cancellation notification before deleting
        $appointment->load(['patient.user', 'doctor.user']);
        $this->sendCancellationNotifications($appointment);

        $appointment->delete();

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    /**
     * Send all notifications when appointment is created
     */
    private function sendAppointmentNotifications(Appointment $appointment)
    {
        // 1. Notify Patient
        $appointment->patient->user->notify(
            new AppointmentBooked($appointment)
        );

        // 2. Notify Doctor
        $appointment->doctor->user->notify(
            new NewPatientAppointment($appointment)
        );

        // 3. Notify Other Admins (skip current admin)
        $admins = User::where('role', 'admin')
            // ->where('id', '!=', auth()->id())
            ->get();

        foreach ($admins as $admin) {
            $admin->notify(new NewPatientAppointment($appointment));
        }

        // 4. Schedule Reminders (24h & 12h before)
        $this->scheduleReminders($appointment);

        Log::info("Admin created appointment #{$appointment->id} - Notifications sent to patient, doctor, and admins");
    }


    /**
     * Send confirmation notification
     */
    private function sendConfirmationNotifications(Appointment $appointment)
    {
        $appointment->patient->user->notify(
            new AppointmentConfirmed($appointment)
        );

        $this->scheduleReminders($appointment);

        Log::info("Appointment #{$appointment->id} confirmed - Notification sent to patient");
    }

    /**
     * Send reschedule notification
     */
    private function sendRescheduleNotifications(Appointment $appointment)
    {
        $appointment->patient->user->notify(
            new AppointmentRescheduled($appointment)
        );

        // Reset reminder flags and reschedule
        $appointment->update([
            'reminder_24h_sent_at' => null,
            'reminder_12h_sent_at' => null,
        ]);

        $this->scheduleReminders($appointment);

        Log::info("Appointment #{$appointment->id} rescheduled - Notification sent to patient");
    }

    /**
     * Send cancellation notification
     */
    private function sendCancellationNotifications(Appointment $appointment)
    {
        $appointment->patient->user->notify(
            new AppointmentCancelled($appointment)
        );

        Log::info("Appointment #{$appointment->id} cancelled - Notification sent to patient");
    }
    /**
     * Schedule reminder jobs (24h & 12h before)
     */
    private function scheduleReminders(Appointment $appointment)
    {
        $appointmentDateTime = Carbon::parse($appointment->schedule_date)
            ->setTimeFromTimeString($appointment->schedule_time);

        // Schedule 24-hour reminder
        $reminder24h = $appointmentDateTime->copy()->subHours(24);
        if ($reminder24h->isFuture()) {
            SendAppointmentReminderJob::dispatch($appointment, 24)
                ->delay($reminder24h);

            Log::info("24h reminder scheduled for appointment #{$appointment->id} at {$reminder24h}");
        }

        // Schedule 12-hour reminder
        $reminder12h = $appointmentDateTime->copy()->subHours(12);
        if ($reminder12h->isFuture()) {
            SendAppointmentReminderJob::dispatch($appointment, 12)
                ->delay($reminder12h);

            Log::info("12h reminder scheduled for appointment #{$appointment->id} at {$reminder12h}");
        }
    }
}
