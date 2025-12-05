@extends('layouts.admin')

@section('title', 'Payments')

@section('breadcrumb')
    <a href="{{ route('admin.payments.index') }}" class="hover:underline">Payments</a>
@endsection


@section('content')

@if(session('success'))
    <x-admin.alert type="success" :message="session('success')" />
@endif

@if(session('error'))
    <x-admin.alert type="error" :message="session('error')" />
@endif

<x-admin.card>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold">All Payments</h2>
    </div>

    <div class="flex items-center justify-between mb-10">
        <div></div>
        <form method="GET">
            @csrf
            <div class="flex items-center space-x-2">

            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <x-admin.table>
            <thead>
                <form method="GET">
                    @csrf
                <tr class="text-left overflow-x-auto">
                    <td class="px-4 py-2">#</td>
                    <td class="px-4 py-2 ">Amount</td>
                    <td class="px-4 py-2">
                        <select name="method"
                        id="method"
                        class="text-gray-400 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"
                        >
                        <option value="">Payment Method</option>
                        <option value="card">Card</option>
                        <option value="cash">Cashe</option>
                        <option value="wallet">Wallet</option>
                    </select>
                    </td>
                    <td class="px-4 py-2">
                        <select name="status" id="status" class="text-gray-400 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                            <option value="">Status</option>
                            <option value="paid">Paid</option>
                            <option value="refunded">Refunded</option>
                            <option value="failed">Failed</option>
                        </select>
                    </td>
                    <td class="px-4 py-2">
                        <input type="text" name="transaction" placeholder="Transaction" class="w-40 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                    </td>
                    <td class="px-4 py-2">
                        <input type="text" name="appointment" placeholder="Appointment" class="w-28 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">

                    </td>
                    <td class="px-4 py-2">
                        <input type="text" name="patient" placeholder="Patient" class="w-40 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">

                    </td>
                    <td class="px-4 py-2"><x-admin.button>Filter</x-admin.button></td>
                </tr>
            </form>
            </thead>
            <tbody>
                @forelse ($payments as $payment)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $payment->id }}</td>
                        <td class="px-4 py-3"> $ {{ $payment->amount }}</td>
                        <td class="px-4 py-3 flex items-center justify-center gap-2 {{
                            $payment->method === 'card' ? 'text-blue-800' : (
                            $payment->method === 'wallet' ? 'text-green-800' : 'text-yellow-500')
                         }}">

                            <span class="material-icons  text-lg drop-shadow {{
                                $payment->method === 'card' ? 'text-blue-800' : (
                                $payment->method === 'wallet' ? 'text-green-800' : 'text-yellow-500')
                            }}">{{
                                    $payment->method === 'card' ? 'payment' : (
                                        $payment->method === 'wallet' ? 'wallet' : 'attach_money'
                                    )
                                }}
                            </span>
                            {{ $payment->method }}
                        </td>
                        <td class="px-4 py-3"> <span class="px-2 py-1 rounded-lg text-xs shadow {{
                            $payment->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : (
                            $payment->status === 'refunded' ? 'bg-gray-100 text-gray-800' : 'bg-rose-100 text-rose-800')
                         }}">{{ $payment->status }}</span></td>
                        <td class="px-4 py-3">{{ $payment->transaction_id }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.appointments.show', $payment->appointment_id) }}" class="hover:underline">
                                {{ $payment->appointment->id }} ➝
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.patients.show', $payment->patient) }}" class="hover:underline">{{ $payment->patient->user->name }}</a>
                        </td>
                        <td class="px-4 py-3"><a href="{{ route('admin.payments.show', $payment) }}" class="hover:underline">View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-6 text-center">No payments found.</td></tr>
                @endforelse
            </tbody>
        </x-admin.table>
    </div>

    <div class="mt-4">
        {{ $payments->links() }}
    </div>
</x-admin.card>
@endsection
