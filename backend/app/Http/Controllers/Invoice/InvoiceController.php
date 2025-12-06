<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }

    public function generatePdf(Invoice $invoice) {
        $appointment_id = $invoice->appointment_id;
        $appointment = Appointment::findOrFail($appointment_id);
        $appointment->load(['patient.user', 'doctor.user']);
        $payment = Payment::firstWhere('appointment_id', $appointment_id);
        // 1. load the view
        $pdf = Pdf::loadView('pdf.invoice', compact('invoice', 'appointment', 'payment'));

        // 2. build the file name
        $fileName = 'invoice_' . $invoice->id . '_' . time() . '.pdf';

        // Storage::makeDirectory('public/invoices');
        // 3. save to the storage
        // Storage::put('public/invoices/'. $fileName, $pdf->output());
        Storage::disk('public')->put('invoices/' . $fileName, $pdf->output());

        // 4. update the invoice
        $invoice->pdf_path = 'invoices/' . $fileName;
        $invoice->save();

        return true;
    }
}
