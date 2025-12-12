<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{
    /**
     * Create PayPal payment
     */
    public function createPayment(Request $request)
    {
        try {
            $appointment = Appointment::findOrFail($request->appointment_id);

            // Initialize PayPal provider
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            // Create order
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
                            "currency_code" => config('paypal.currency', 'USD'),
                            "value" => number_format($request->amount, 2, '.', '')
                        ],
                        "description" => "Payment for Appointment #{$appointment->id}"
                    ]
                ]
            ]);

            if (isset($order['id']) && $order['id'] != null) {
                // Store pending payment in database
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

            return redirect()->route('admin.payments.index')
                ->with('error', 'Failed to create PayPal payment order');

        } catch (\Exception $e) {
            Log::error('PayPal Create Payment Error: ' . $e->getMessage());
            return redirect()->route('admin.payments.index')
                ->with('error', 'PayPal payment creation failed: ' . $e->getMessage());
        }
    }


   /**
     * Handle successful payment
     */
    public function paymentSuccess(Request $request)
    {
        try {
            // Get the token from the request
            $token = $request->query('token');

            if (!$token) {
                return redirect()->route('admin.payments.index')
                    ->with('error', 'Payment token not found');
            }

            // Initialize PayPal provider
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            // Capture the payment
            $result = $provider->capturePaymentOrder($token);

            if (isset($result['status']) && $result['status'] === 'COMPLETED') {
                // Find the payment by transaction_id
                $payment = Payment::where('transaction_id', $token)->first();

                if ($payment) {
                    // Update payment status
                    $payment->update([
                        'status' => 'paid'
                    ]);

                    // Create or update invoice
                    $invoice = Invoice::firstOrCreate(
                        ['appointment_id' => $payment->appointment_id],
                        [
                            'total' => $payment->amount,
                            'pdf_path' => null
                        ]
                    );

                    // Generate PDF invoice
                    app(\App\Http\Controllers\Invoice\InvoiceController::class)->generatePdf($invoice);

                    Log::info("PayPal payment successful for appointment #{$payment->appointment_id}");

                    return redirect()
                        ->route('admin.payments.show', $payment)
                        ->with('success', 'Payment completed successfully via PayPal!');
                }

                return redirect()->route('admin.payments.index')
                    ->with('error', 'Payment record not found in database');
            }

            // Payment was not completed
            return redirect()->route('admin.payments.index')
                ->with('error', 'Payment was not completed. Status: ' . ($result['status'] ?? 'Unknown'));

        } catch (\Exception $e) {
            Log::error('PayPal Success Callback Error: ' . $e->getMessage());
            return redirect()->route('admin.payments.index')
                ->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle cancelled payment
     */
    public function paymentCancel(Request $request)
    {
        try {
            $token = $request->query('token');

            if ($token) {
                // Update payment status to failed
                $payment = Payment::where('transaction_id', $token)->first();
                if ($payment) {
                    $payment->update(['status' => 'failed']);
                }
            }

            return redirect()
                ->route('admin.payments.index')
                ->with('error', 'PayPal payment was cancelled');

        } catch (\Exception $e) {
            Log::error('PayPal Cancel Error: ' . $e->getMessage());
            return redirect()->route('admin.payments.index')
                ->with('error', 'Error handling payment cancellation');
        }
    }
}
