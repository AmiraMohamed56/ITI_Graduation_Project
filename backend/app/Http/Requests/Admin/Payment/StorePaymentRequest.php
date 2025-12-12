<?php

namespace App\Http\Requests\Admin\Payment;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'appointment_id' => 'required|exists:appointments,id',
            'patient_id' => 'required|exists:patients,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:paid,refunded,failed',
            'method' => 'required|in:card,wallet,cash',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:5120',
            // 'transaction_id' => 'nullable|string|max:191'
        ];
    }

        public function messages(): array
    {
        return [
            'payment_proof.image' => 'Payment proof must be an image file.',
            'payment_proof.mimes' => 'Payment proof must be a JPEG, PNG, JPG, or PDF file.',
            'payment_proof.max' => 'Payment proof must not exceed 5MB.',
        ];
    }

}
