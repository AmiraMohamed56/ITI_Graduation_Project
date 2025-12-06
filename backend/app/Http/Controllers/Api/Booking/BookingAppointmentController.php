<?php
namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Http\Requests\Booking\StoreAppointmentRequest;
use App\Http\Resources\Booking\BookingAppointmentResource;
use Illuminate\Http\Request;

class BookingAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $q = Appointment::query();

        if ($request->filled('doctor_id')) {
            $q->where('doctor_id', $request->doctor_id);
        }
        if ($request->filled('doctor_schedule_id')) {
            $q->where('doctor_schedule_id', $request->doctor_schedule_id);
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

        return (new BookingAppointmentResource($appt))->response()->setStatusCode(201);
    }
}
