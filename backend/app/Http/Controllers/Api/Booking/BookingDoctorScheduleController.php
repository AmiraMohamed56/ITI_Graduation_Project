<?php
namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use App\Http\Resources\Booking\BookingDoctorScheduleResource;
use Illuminate\Http\Request;

class BookingDoctorScheduleController extends Controller
{
    public function index(Request $request)
    {
        $q = DoctorSchedule::query();

        if ($request->filled('doctor_id')) {
            $q->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('day_of_week')) {
            $q->where('day_of_week', $request->day_of_week);
        }

        // return array matching mock shape
        return BookingDoctorScheduleResource::collection($q->get());
    }
}
