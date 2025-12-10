<?php
namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Http\Resources\Booking\BookingDoctorResource;
use Illuminate\Http\Request;

class BookingDoctorController extends Controller
{
    public function index(Request $request)
    {
        // filter by specialty_id
        $doctors = Doctor::with(['user','specialty'])->get();
        return BookingDoctorResource::collection($doctors);
    }
}
