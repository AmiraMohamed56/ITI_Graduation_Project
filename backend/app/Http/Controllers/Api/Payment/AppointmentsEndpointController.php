<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Http\Resources\Payment\AppointmentsEndpointResource;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;

class AppointmentsEndpointController extends Controller
{
    public function index(Request $request) {
        $appointments = Appointment::get();
        return AppointmentsEndpointResource::collection($appointments);
    }
}
