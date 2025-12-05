@extends('layouts.admin')
@section('title', 'create')
@section('breadcrumb')
    <a href="{{ route('admin.payments.index') }} " class="hover:underline">Payments</a> / <span>Create Payment</span>
@endsection

@section('content')
<!-- validation errors -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <x-admin.alert type="error" :message="$error" />
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <x-admin.alert type="success" :message="session('success')" />
@endif

@if(session('error'))
    <x-admin.alert type="error" :message="session('error')" />
@endif
<x-admin.card>
    <h2 class="text-lg font-semibold mb-6">Create New Payment</h2>

    <form action="{{ route('admin.payments.store') }}" method="POST" class="space-y-6">
        @csrf

        <x-admin.input type="number" label="Appointment_id" name="appointment_id" value="{{ $appointment->id }}"  readonly />

        <x-admin.input type="text" label="" name="patient_id" value="{{ $appointment->patient_id }}" hidden />
        <x-admin.input type="text" label="Patient" name="fake" value="{{ $appointment->patient->user->name }}" readonly />

        <x-admin.input type="number" label="Price" name="amount" />


        <div class="flex items-center justify-start gap-10 ">

            <div>
                <label for="status" class="block text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select name="status" id="status" class="w-80 px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                    <option value="">select status</option>
                    <option value="paid">paid</option>
                    <option value="refunded">Refunded</option>
                    <option value="failed">Failed</option>
                </select>
            </div>

            <div>
                <label for="method" class="block text-gray-700 dark:text-gray-300 mb-1">Method</label>
                <select name="method" id="method" class="w-80 px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                    <option value="">select method</option>
                    <option value="cash">Cash</option>
                    <option value="wallet">Wallet</option>
                    <option value="card">Card</option>
                </select>
            </div>
        </div>

        {{-- <x-admin.input type="text" label="Transaction_id" name="transaction_id" /> --}}

        <div class="flex items-center justify-end gap-6">

            <button type="button" onclick="window.history.back()" class="px-4 py-2 text-sm inline-flex items-center justify-center font-medium
            rounded-xl transition-all duration-200 select-none
            focus:outline-none focus:ring-2 focus:ring-offset-2
            disabled:opacity-60 disabled:cursor-not-allowed bg-gray-200 text-gray-800 hover:bg-gray-300 active:bg-gray-400
            focus:ring-gray-400 shadow-sm hover:shadow drop-shadow">
                Cancel
            </button>
            <x-admin.button type="primary">Create Payment</x-admin.button>

        </div>
    </form>

</x-admin.card>
@endsection
