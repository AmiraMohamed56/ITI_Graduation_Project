<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Notifications\AppointmentBooked;
use App\Notifications\NewPatientAppointment;
use App\Jobs\SendAppointmentReminderJob;
use App\Http\Requests\Booking\StoreAppointmentRequest;
use App\Http\Resources\Booking\BookingAppointmentResource;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BookingAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $q = Appointment::query();

        if ($request->filled('doctor_id')) {
            $q->where('doctor_id', $request->doctor_id);
        }
        if ($request->filled('schedule_date')) {
            $q->where('schedule_date', $request->schedule_date);
        }

        $appointments = $q->get();

        return BookingAppointmentResource::collection($appointments);
    }

    public function store(StoreAppointmentRequest $request)
    {
        // Prevent double-booking
        $exists = Appointment::where('doctor_id', $request->doctor_id)
            ->where('schedule_date', $request->schedule_date)
            ->where('schedule_time', $request->schedule_time)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Time slot already booked'
            ], 409);
        }

        $appt = Appointment::create(array_merge($request->validated(), [
            'status' => 'pending'
        ]));

        // Load relationships
        $appt->load(['patient.user', 'doctor.user']);

        // SEND NOTIFICATIONS
        $this->sendAppointmentNotifications($appt);

        return (new BookingAppointmentResource($appt))
            ->additional([
                'message' => 'Appointment created successfully. You will receive reminders 24 and 12 hours before your appointment.'
            ])
            ->response()
            ->setStatusCode(201);
    }

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

        // 3. Notify All Admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewPatientAppointment($appointment));
        }

        // 4. Schedule Reminders
        $this->scheduleReminders($appointment);

        Log::info("Appointment #{$appointment->id} created - All notifications sent");
    }


    //Schedule reminder jobs for the appointment
        private function scheduleReminders(Appointment $appointment)
    {
        $appointmentDateTime = Carbon::parse($appointment->schedule_date)
            ->setTimeFromTimeString($appointment->schedule_time);

        // Schedule 24-hour reminder
        $reminder24h = $appointmentDateTime->copy()->subHours(24);
        if ($reminder24h->isFuture()) {
            SendAppointmentReminderJob::dispatch($appointment, 24)
                ->delay($reminder24h);
        }

        // Schedule 12-hour reminder
        $reminder12h = $appointmentDateTime->copy()->subHours(12);
        if ($reminder12h->isFuture()) {
            SendAppointmentReminderJob::dispatch($appointment, 12)
                ->delay($reminder12h);
        }
    }





    //Update appointment reschedule reminders if date or time changed
    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $dateChanged = $request->filled('schedule_date') &&
                       $request->schedule_date != $appointment->schedule_date;
        $timeChanged = $request->filled('schedule_time') &&
                       $request->schedule_time != $appointment->schedule_time;

        $appointment->update($request->all());

        // If date or time changed, reschedule reminders
        if ($dateChanged || $timeChanged) {
            // Reset reminder flags
            $appointment->update([
                'reminder_24h_sent_at' => null,
                'reminder_12h_sent_at' => null,
            ]);

            // Schedule new reminders
            $this->scheduleReminders($appointment);
        }

        return response()->json([
            'message' => 'Appointment updated successfully',
            'data' => new BookingAppointmentResource($appointment)
        ]);
    }


    public function cancel($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => 'cancelled']);
        // The job will check status and not send if cancelled
        // No need to delete scheduled jobs
        return response()->json([
            'message' => 'Appointment cancelled. Reminders will not be sent.'
        ]);
    }
}
