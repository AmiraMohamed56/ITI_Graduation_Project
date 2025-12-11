<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Http\Resources\Payment\PaymentEndpointResource;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentsEndpointController extends Controller
{
    public function index(Request $request) {
        $payemntst = Payment::get();
        return PaymentEndpointResource::collection($payemntst);
    }
}
