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

use function Symfony\Component\Clock\now;

class AdminAppointmentController extends Controller
{
    public function index(Request $request) {
        $query = Appointment::with(['patient.user', 'doctor.user', 'doctorSchedule']);

        // filters
        if($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('patient.user', fn($q1) => $q1->where('name', 'like', "%{$q}%"))->orWhereHas('doctor.user', fn($q1) => $q1->where('name', 'like', "%{$q}%"));
        }

        if($request->filled('date_from')) {
            $query->where('schedule_date', '>=', $request->date_from);
        }

        if($request->filled('date_to')) {
            $query->where('schedule_date', '<=', $request->date_to);
        }

        // if($request->filled('id')) {
        //     $query->where('id', $request->id);
        // }

        $appointments = $query->orderBy('schedule_date', 'desc')->orderBy('schedule_time', 'desc')->paginate(15)->withQueryString();

        return view('admin.appointments.index', compact('appointments'));

    }

    public function show(Appointment $appointment) {
        $appointment->load(['patient.user', 'doctor.user','doctor.specialty', 'doctorSchedule']);
        $payment = Payment::where('appointment_id', $appointment->id)->first();
        // dd($payment);
        return view('admin.appointments.show', compact('appointment', 'payment'));
    }

    public function create() {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with(['user','schedules','appointments' => function($q){
            $q->where('schedule_date', '>=', now())->where('status','!=','cancelled');
        }])->get();

        return view('admin.appointments.create', compact('patients', 'doctors'));
    }


    public function store(Request $request) {

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

        $appointment= Appointment::with(['patient.user', 'doctor.user','doctor.specialty', 'doctorSchedule'])->find($appointment->id);
        $payment = Payment::where('appointment_id', $appointment->id)->first();

        // return Redirect()->route('admin.appointments.show', compact('appointment', 'payment'))->with('success', 'Appointment created successfully.');
        return view('admin.appointments.show', compact('appointment', 'payment'));

    }

    public function edit(Appointment $appointment) {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with(['user', 'schedules', 'appointments' => function($q) use ($appointment) {
            $q->where('schedule_date', '>=', now())
              ->where('status', '!=', 'cancelled')
              ->where('id', '!=', $appointment->id); // exclude current appointment
        }])->get();

        return view('admin.appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(Request $request, Appointment $appointment) {
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

        $payment = Payment::where('appointment_id', $appointment->id)->first();

        return redirect()->route('admin.appointments.show', compact('appointment', 'payment'))->with('success', 'Appointment updated successfully.');


    }

    public function destroy(Appointment $appointment)
    {
        //  delete related payment
        Payment::where('appointment_id', $appointment->id)->delete();

        $appointment->delete();

        return redirect()->route('admin.appointments.index')
                         ->with('success', 'Appointment deleted successfully.');
    }

}
