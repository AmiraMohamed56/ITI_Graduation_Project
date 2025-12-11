<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentEndpointResource extends JsonResource
{

    public function toArray(Request $request): array
    {

            return [
                'id' => $this->id,
                'appointment_id' => $this->appointment_id,
                'patient_id' => $this->patient_id,
                'amount' => $this->amount,
                'status' => $this->status,
                'method' => $this->method

            ];
        
    }
}
