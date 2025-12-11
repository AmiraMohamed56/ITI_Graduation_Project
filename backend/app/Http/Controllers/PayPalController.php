<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayPalController extends Controller
{
    /**
     * Create PayPal payment
     */
    public function createPayment(Request $request)
    {
        $appointment = Appointment::findOrFail($request->appointment_id);

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $order = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.success'),
                "cancel_url" => route('paypal.cancel'),
            ],
            "purchase_units" => [
                [
                    "reference_id" => "appointment_" . $appointment->id,
                    "amount" => [
                        "currency_code" => config('paypal.currency'),
                        "value" => $request->amount
                    ],
                    "description" => "Payment for Appointment #{$appointment->id}"
                ]
            ]
        ]);

        if (isset($order['id']) && $order['id'] != null) {
            // Store pending payment
            Payment::create([
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
                'amount' => $request->amount,
                'method' => 'paypal',
                'status' => 'pending',
                'transaction_id' => $order['id']
            ]);

            // Redirect to PayPal approval URL
            foreach ($order['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        }

        return redirect()->back()->with('error', 'Failed to create PayPal payment');
    }

    /**
     * Handle successful payment
     */
    public function paymentSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $result = $provider->capturePaymentOrder($request->token);

        if (isset($result['status']) && $result['status'] === 'COMPLETED') {
            $orderId = $result['id'];

            // Update payment record
            $payment = Payment::where('transaction_id', $orderId)->first();

            if ($payment) {
                $payment->update([
                    'status' => 'paid',
                    'transaction_id' => $orderId
                ]);

                // Generate invoice
                $invoice = Invoice::firstOrCreate(
                    ['appointment_id' => $payment->appointment_id],
                    [
                        'total' => $payment->amount,
                        'pdf_path' => null
                    ]
                );

                app(\App\Http\Controllers\Invoice\InvoiceController::class)->generatePdf($invoice);

                Log::info("PayPal payment successful for appointment #{$payment->appointment_id}");

                return redirect()
                    ->route('admin.payments.show', $payment)
                    ->with('success', 'Payment completed successfully via PayPal!');
            }
        }

        return redirect()
            ->route('admin.payments.index')
            ->with('error', 'Payment verification failed');
    }

    /**
     * Handle cancelled payment
     */
    public function paymentCancel(Request $request)
    {
        // Optionally update payment status to 'failed'
        if ($request->has('token')) {
            $payment = Payment::where('transaction_id', $request->token)->first();
            if ($payment) {
                $payment->update(['status' => 'failed']);
            }
        }

        return redirect()
            ->route('admin.appointments.index')
            ->with('error', 'PayPal payment was cancelled');
    }
}
