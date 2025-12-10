<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }

        .container {
            width: 90%;
            margin: auto;
            padding: 20px;
            border: 1px solid #eee;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        header h1 {
            color: #4CAF50;
            margin: 0;
        }

        .company-info {
            text-align: right;
            font-size: 10px;
            color: #555;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            background-color: #f4f4f4;
            padding: 5px 10px;
            font-weight: bold;
            border-left: 4px solid #4CAF50;
            margin-bottom: 10px;
        }

        .details div {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        .totals {
            margin-top: 20px;
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }

        .totals table {
            width: 300px;
            border: none;
        }

        .totals td {
            border: none;
            padding: 5px 10px;
        }

        footer {
            border-top: 1px solid #eee;
            padding-top: 10px;
            text-align: center;
            font-size: 10px;
            color: #888;
            margin-top: 30px;
        }

        /* .status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            color: white;
            font-size: 10px;
            vertical-align: middle;
        }

        .status.paid { background-color: #4CAF50; }
        .status.pending { background-color: #FF9800; }
        .status.refunded { background-color: #F44336; } */

    </style>
</head>
<body>

<div class="container">

<header>
    <h1>CareNOva</h1>
    <div class="company-info">
        123 Health St, City, Country<br>
        +20 123 456 789<br>
        info@smartclinic.com
    </div>
</header>

<div class="section">
    <div class="section-title">Invoice Details</div>
    <div class="details">
        <div><strong>Invoice #: </strong>{{ $invoice->id }}</div>
        <div><strong>Date: </strong>{{ $invoice->created_at->format('Y-m-d H:i') }}</div>
        <div><strong>Status: </strong>{{ strtolower($payment->status ?? 'pending') }}
            {{-- <span class="status {{ strtolower($payment->status ?? 'pending') }}">
                {{ ucfirst($payment->status ?? 'Pending') }}
            </span> --}}
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title">Patient & Doctor Info</div>
    <div class="details">
        <div><strong>Patient: </strong>{{ $appointment->patient->user->name }}</div>
        <div><strong>Doctor: </strong>{{ $appointment->doctor->user->name }}</div>
    </div>
</div>

<div class="section">
    <div class="section-title">Payment Details</div>
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount (EGP)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Consultation Fee</td>
                <td>{{ $invoice->total }}</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="totals">
    <table>
        <tr>
            <td><strong>Total:</strong></td>
            <td>{{ $invoice->total }} EGP</td>
        </tr>
    </table>
</div>

<footer>
    Thank you for choosing CareNova. Visit us at www.carenova.com
</footer>

</div>

</body>
</html>
