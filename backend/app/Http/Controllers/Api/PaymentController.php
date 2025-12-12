<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Get appointment payment information
     */
    public function getAppointmentPaymentInfo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'appointment_id' => 'required|exists:appointments,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid appointment ID',
                    'errors' => $validator->errors()
                ], 422);
            }

            $appointment = Appointment::with(['patient', 'doctor.user'])
                ->findOrFail($request->appointment_id);

            // Check if user is authorized to view this appointment
            if ($appointment->patient_id !== auth()->user()->patient->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            return response()->json([
                'status' => true,
                'message' => 'Appointment info retrieved successfully',
                'data' => [
                    'appointment_id' => $appointment->id,
                    'patient_id' => $appointment->patient_id,
                    'doctor_name' => $appointment->doctor->user->name ?? 'N/A',
                    'schedule_date' => $appointment->schedule_date,
                    'schedule_time' => $appointment->schedule_time,
                    'amount' => $appointment->price ?? 100.00, // Default price or use from appointment
                    'type' => $appointment->type,
                    'status' => $appointment->status
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching appointment info: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve appointment information',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store payment
     */
    public function store(Request $request)
    {
        try {
            // Validation rules
            $rules = [
                'appointment_id' => 'required|exists:appointments,id',
                'patient_id' => 'required|exists:patients,id',
                'amount' => 'required|numeric|min:0',
                'method' => 'required|in:cash,card,wallet,paypal'
            ];

            // Add conditional validation for card payment
            if ($request->method === 'card') {
                $rules['card_holder'] = 'required|string|max:255';
                $rules['card_number'] = 'required|string|size:16';
                $rules['expiry_date'] = 'required|date_format:Y-m';
                $rules['cvv'] = 'required|string|size:3';
            }

            // Add conditional validation for wallet payment
            if ($request->method === 'wallet') {
                $rules['payment_proof'] = 'required|file|mimes:jpeg,jpg,png,pdf|max:5120'; // 5MB max
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if payment already exists for this appointment
            $existingPayment = Payment::where('appointment_id', $request->appointment_id)->first();
            if ($existingPayment) {
                return response()->json([
                    'status' => false,
                    'message' => 'Payment already exists for this appointment'
                ], 409);
            }

            // Prepare payment data
            $paymentData = [
                'appointment_id' => $request->appointment_id,
                'patient_id' => $request->patient_id,
                'amount' => $request->amount,
                'method' => $request->method,
                'status' => 'paid', // Set status based on method
                'transaction_id' => 'TXN-' . strtoupper(uniqid())
            ];

            // Handle payment proof upload for wallet
            if ($request->method === 'wallet' && $request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('payment_proofs', $filename, 'public');
                $paymentData['payment_proof'] = $path;
            }

            // For card payments, you might want to integrate with a payment gateway here
            // For now, we'll just store the payment as successful
            if ($request->method === 'card') {
                // In production, integrate with Stripe/PayPal/etc
                // For now, just generate a transaction ID
                $paymentData['transaction_id'] = 'CARD-' . strtoupper(uniqid());
            }

            // Create payment record
            $payment = Payment::create($paymentData);

            // Update appointment status to confirmed after successful payment
            $appointment = Appointment::find($request->appointment_id);
            if ($appointment && $appointment->status === 'pending') {
                $appointment->update(['status' => 'confirmed']);
            }

            return response()->json([
                'status' => true,
                'message' => 'Payment processed successfully',
                'data' => [
                    'payment_id' => $payment->id,
                    'transaction_id' => $payment->transaction_id,
                    'method' => $payment->method,
                    'amount' => $payment->amount,
                    'status' => $payment->status
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Payment processing error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Payment processing failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
