<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Payment\StorePaymentRequest;
use App\Http\Requests\Admin\Payment\UpdatePaymentRequest;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\ViewFinderInterface;
use Pest\Support\View;

use function Pest\Laravel\session;

class AdminPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['appointment.doctor.user', 'patient.user']);

        if($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if($request->filled('method')) {
            $query->where('method', $request->method);
        }

        if($request->filled('transaction')) {
            $query->where('transaction_id', 'like', "%{$request->input('transaction')}%");
        }

        if($request->filled('appointment')) {
            $query->where('appointment_id', $request->appointment);
        }

        if ($request->filled('patient')) {
            $q = $request->patient;
            $query->whereHas('patient.user', fn($q1) => $q1->where('name', 'like', "%{$q}%"));
        }

        $payments = $query->latest()->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $appointment_id = $request->server()['QUERY_STRING'];

        $appointment = Appointment::with('patient.user')
        ->where('id', $appointment_id)
        ->first();
                // dd($appointment);
        // $appointments = Appointment::with('patient.user')->get();
        // $patients = Patient::with('user')->get();

        return view('admin.payments.create', compact('appointment'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request)
    {
        $data = $request->validated();

        $transation_id = 'TXN-' . Str::uuid();
        $data['transaction_id'] = $transation_id;

        Payment::create($data);

        $payment = Payment::where('appointment_id', $data['appointment_id'])->where('patient_id', $data['patient_id'])->first();
        $payment->load(['appointment', 'appointment.doctor.user','patient.user']);

        // creaet invoice record
        if($payment->status == 'paid') {
            $invoice = Invoice::firstOrCreate(
                ['appointment_id' => $payment->appointment_id],
                [
                    'total' => $payment->amount,
                    'pdf_path' => null
                ]
            );
            app(\App\Http\Controllers\Invoice\InvoiceController::class)->generatePdf($invoice);

        }
        return redirect()->route('admin.payments.show', compact('payment'))->with('success', 'Payment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(['appointment.doctor.user', 'patient.user']);
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $payment->load(['appointment.doctor.user', 'patient.user']);
        return view('admin.payments.edit', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $data = $request->validated();
        $transation_id = 'TXN-' . Str::uuid();
        $data['transaction_id'] = $transation_id;
        $payment->update($data);
        $payment->load(['appointment.doctor.user', 'patient.user']);
        return redirect()->route('admin.payments.show', compact('payment'))->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('admin.payments.index')->with('success', 'Payment deleted successfully');
    }
}
