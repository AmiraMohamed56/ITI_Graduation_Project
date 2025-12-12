@extends('layouts.admin')

@section('title', 'Payments')

@section('breadcrumb')
    <a href="{{ route('admin.payments.index') }}" class="hover:underline">Payments</a>
@endsection

@section('content')

<div x-data="{ deleteModal: false, deleteId: null }" class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">



    <div class="max-w-7xl mx-auto py-10 px-6">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-2">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Total Payments</h1>
                <span class="bg-red-600 text-white text-sm font-medium px-3 py-1 rounded">
                    {{ $payments->total() }}
                </span>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.payments.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium">
                   Add Payment
                </a>
            </div>
        </div>

        <!-- Session Messages -->
        @if(session('success'))
            <x-admin.alert type="success" :message="session('success')" />
        @endif

        @if(session('error'))
            <x-admin.alert type="error" :message="session('error')" />
        @endif

        <!-- Filters -->
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <form method="GET" action="{{ route('admin.payments.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Transaction</label>
                    <input type="text" name="transaction" placeholder="Search by transaction..."
                        value="{{ request('transaction') }}"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Appointment</label>
                    <input type="text" name="appointment" placeholder="Appointment ID..."
                        value="{{ request('appointment') }}"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Patient</label>
                    <input type="text" name="patient" placeholder="Patient name..."
                        value="{{ request('patient') }}"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Method</label>
                    <select name="method"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm">
                        <option value="">All Methods</option>
                        <option value="card" {{ request('method')=='card' ? 'selected' : '' }}>Card</option>
                        <option value="cash" {{ request('method')=='cash' ? 'selected' : '' }}>Cash</option>
                        <option value="wallet" {{ request('method')=='wallet' ? 'selected' : '' }}>Wallet</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select name="status"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm">
                        <option value="">All Status</option>
                        <option value="paid" {{ request('status')=='paid' ? 'selected' : '' }}>Paid</option>
                        <option value="refunded" {{ request('status')=='refunded' ? 'selected' : '' }}>Refunded</option>
                        <option value="failed" {{ request('status')=='failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-filter text-xs"></i> Filter
                    </button>

                    <a href="{{ route('admin.payments.index') }}"
                        class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 text-sm font-medium transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-times text-xs"></i> Clear
                    </a>
                </div>

            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow">
            <table class="w-full text-left">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">ID</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">Amount</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">Method</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">Status</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">Transaction</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">Appointment</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">Patient</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($payments as $payment)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">#{{ $payment->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">$ {{ $payment->amount }}</td>
                        <td class="px-6 py-4 flex items-center gap-2 text-sm">
                            <span
                                class="material-icons text-lg {{ $payment->method === 'card' ? 'text-blue-800' : ($payment->method === 'wallet' ? 'text-green-800' : 'text-yellow-500') }}">
                                {{ $payment->method === 'card' ? 'payment' : ($payment->method === 'wallet' ? 'wallet' : 'attach_money') }}
                            </span>
                            {{ ucfirst($payment->method) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-lg text-xs shadow {{ $payment->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : ($payment->status === 'refunded' ? 'bg-gray-100 text-gray-800' : 'bg-rose-100 text-rose-800') }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $payment->transaction_id }}</td>
                        <td class="px-6 py-4 text-sm text-blue-600 dark:text-blue-400">
                            <a href="{{ route('admin.appointments.show', $payment->appointment_id) }}" class="hover:underline">
                                #{{ $payment->appointment->id }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-blue-600 dark:text-blue-400">
                            <a href="{{ route('admin.patients.show', $payment->patient) }}" class="hover:underline">
                                {{ $payment->patient->user->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 flex space-x-2">
                            <a href="{{ route('admin.payments.show', $payment) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <span class="material-icons text-base">visibility</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-6 text-gray-500 dark:text-gray-400">
                            No payments found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $payments->links() }}
        </div>

    </div>

</div>

@endsection
