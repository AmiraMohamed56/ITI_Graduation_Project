<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
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
                'appointment_id' => 'required|integer|exists:appointments,id'
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
            if ($appointment->patient_id !== auth()->id()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access to appointment'
                ], 403);
            }

            // Check if payment already exists
            $existingPayment = Payment::where('appointment_id', $appointment->id)->first();
            if ($existingPayment) {
                return response()->json([
                    'status' => false,
                    'message' => 'Payment already processed for this appointment',
                    'payment' => $existingPayment
                ], 409);
            }

            return response()->json([
                'status' => true,
                'message' => 'Appointment information retrieved successfully',
                'data' => [
                    'appointment_id' => $appointment->id,
                    'patient_id' => $appointment->patient_id,
                    'doctor_name' => $appointment->doctor->user->name ?? 'N/A',
                    'schedule_date' => $appointment->schedule_date,
                    'schedule_time' => $appointment->schedule_time,
                    'type' => $appointment->type,
                    'amount' => $appointment->price ?? 200.00, // Default price if not set
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error getting appointment info: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve appointment information',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new payment
     */
    public function store(Request $request)
    {
        try {
            // Validation rules
            $rules = [
                'appointment_id' => 'required|integer|exists:appointments,id',
                'patient_id' => 'required|integer|exists:patients,id',
                'amount' => 'required|numeric|min:0',
                'method' => 'required|in:cash,card,wallet,paypal',
            ];

            // Add conditional validation for wallet payment
            if ($request->method === 'wallet') {
                $rules['payment_proof'] = 'required|file|mimes:jpeg,jpg,png,pdf|max:5120'; // 5MB max
            }

            // Add conditional validation for card payment
            if ($request->method === 'card') {
                $rules['card_holder'] = 'required|string|max:255';
                $rules['card_number'] = 'required|string|size:16';
                $rules['expiry_date'] = 'required|date_format:Y-m|after_or_equal:' . date('Y-m');
                $rules['cvv'] = 'required|string|size:3';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if user is authorized
            if ($request->patient_id !== auth()->id()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized: Patient ID mismatch'
                ], 403);
            }

            // Check if payment already exists
            $existingPayment = Payment::where('appointment_id', $request->appointment_id)->first();
            if ($existingPayment) {
                return response()->json([
                    'status' => false,
                    'message' => 'Payment already exists for this appointment'
                ], 409);
            }

            // Get appointment
            $appointment = Appointment::findOrFail($request->appointment_id);

            // Verify appointment belongs to patient
            if ($appointment->patient_id !== $request->patient_id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Appointment does not belong to this patient'
                ], 403);
            }

            // Handle payment proof upload for wallet
            $paymentProofPath = null;
            if ($request->method === 'wallet' && $request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $fileName = 'payment_' . $request->appointment_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $paymentProofPath = $file->storeAs('payment_proofs', $fileName, 'public');
            }

            // Generate transaction ID
            $transactionId = $this->generateTransactionId($request->method);

            // Determine payment status based on method
            $status = 'paid'; // Default for cash and card
            if ($request->method === 'wallet') {
                $status = 'paid'; // Or 'pending' if you want admin approval
            }

            // Create payment record
            $payment = Payment::create([
                'appointment_id' => $request->appointment_id,
                'patient_id' => $request->patient_id,
                'amount' => $request->amount,
                'method' => $request->method,
                'status' => $status,
                'transaction_id' => $transactionId,
                'payment_proof' => $paymentProofPath,
            ]);

            // Update appointment status to confirmed after payment
            $appointment->update(['status' => 'confirmed']);

            return response()->json([
                'status' => true,
                'message' => 'Payment processed successfully',
                'data' => [
                    'payment_id' => $payment->id,
                    'transaction_id' => $payment->transaction_id,
                    'status' => $payment->status,
                    'amount' => $payment->amount,
                    'method' => $payment->method,
                    'appointment_status' => $appointment->status,
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

    /**
     * Generate unique transaction ID
     */
    private function generateTransactionId($method)
    {
        $prefix = strtoupper(substr($method, 0, 3));
        return $prefix . '-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -8));
    }

    /**
     * Get all payments for authenticated patient
     */
    public function index()
    {
        try {
            $payments = Payment::where('patient_id', auth()->id())
                ->with('appointment.doctor.user')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'data' => $payments
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve payments',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
