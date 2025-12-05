@extends('layouts.admin')
@section('title', 'Payment')
@section('breadcrumb')
    <a href="{{ route('admin.payments.index') }}" class="hover:underline">Payments</a> / <span>#{{ $payment->id }}</span>
@endsection

@section('content')

{{-- validation errors --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <x-admin.alert type="error" :message="$error" />
            @endforeach
        </ul>
    </div>
@endif

{{-- session --}}
@if(session('success'))
    <x-admin.alert type="success" :message="session('success')" />
@endif

@if(session('error'))
    <x-admin.alert type="error" :message="session('error')" />
@endif

<x-admin.card>
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold mb-6">Payment infromation</h2>

        @if($payment->appointment->invoice && $payment->appointment->invoice->pdf_path)
            <a href="{{ asset('storage/' . $payment->appointment->invoice->pdf_path) }}"
            class="btn btn-primary" download>
            <x-admin.button size="sm">Download Invoice</x-admin.button>
            </a>
        @endif
    </div>

    <div class="space-y-2">
        <div><strong>Id: </strong> {{ $payment->id }}</div>
        <div><strong>Appointment id: </strong> {{ $payment->appointment->id }}</div>
        <div><strong>Patient: </strong> {{ $payment->patient->user->name }}</div>
        <div><strong>Price: </strong> ${{ $payment->amount }}</div>
        <div><strong>Status: </strong>
            <span class="px-2 py-1 rounded-lg text-xs shadow {{
                $payment->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : (
                $payment->status === 'refunded' ? 'bg-gray-100 text-gray-800' : 'bg-rose-100 text-rose-800')
                }}">{{ $payment->status }}
             </span>
        </div>
        <div class="flex items-center"><strong>Method: </strong>

                <span class="material-icons  text-lg drop-shadow {{
                    $payment->method === 'card' ? 'text-blue-800' : (
                    $payment->method === 'wallet' ? 'text-green-800' : 'text-yellow-500')
                }}">&nbsp;{{
                        $payment->method === 'card' ? 'payment' : (
                            $payment->method === 'wallet' ? 'wallet' : 'attach_money'
                        )
                    }}
                </span>
                <span class="  {{
                    $payment->method === 'card' ? 'text-blue-800' : (
                    $payment->method === 'wallet' ? 'text-green-800' : 'text-yellow-500')
                    }}">
                    {{ $payment->method }}
                </span>
        </div>
        <div><strong>Transaction id: </strong> {{ $payment->transaction_id }}</div>
        <div><strong>Created at: </strong> {{ $payment->created_at->format('d-m-Y H:i:s') }}</div>
        <div><strong>Updated at: </strong>{{ $payment->updated_at->format('d-m-Y H:i:s') }}</div>

    </div>
    <div class="mt-10 flex justify-between">
        <div></div>
        <div class="flex gap-4">
            {{-- delete button with a confirmation modal --}}
            {{-- trigger delete modal --}}
            <x-admin.button type="danger" id="delete-button">Delete</x-admin.button>

            <!-- Confirmation Modal (Initially Hidden) -->
            <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
                <div class="bg-white dark:bg-gray-800 shadow rounded p-6 w-96">
                    <h2 class="text-xl mb-4">Are you sure you want to delete this payment?</h2>
                    <div class="flex justify-end gap-4">
                        <!-- Cancel Button -->
                        <x-admin.button id="cancel-button" type="secondary" size="sm">Cancel</x-admin.button>

                        {{-- delete form --}}
                        <form id="delete-form" action="{{ route('admin.payments.destroy', $payment) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <x-admin.button type="danger" size="sm">Cofirm</x-admin.button>
                        </form>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.payments.edit', $payment) }}"><x-admin.button type="secondary">Edit</x-admin.button></a>
        </div>
    </div>
</x-admin.card>
@endsection
@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const deleteButton = document.getElementById("delete-button");
        const cancelButton = document.getElementById("cancel-button");
        const modal = document.getElementById("modal");
        const deleteForm = document.getElementById("delete-form");

        // Show Modal
        deleteButton.addEventListener("click", function() {
            modal.classList.remove("hidden");
        });

        // Hide Modal (Cancel)
        cancelButton.addEventListener("click", function() {
            modal.classList.add("hidden");
        });

        // submit the form after confirmation
        // deleteForm.addEventListener("submit", function(event) {
        //     // You could add a confirmation prompt if you want a final check
        //     if (!confirm("Are you sure you want to delete this payment?")) {
        //         event.preventDefault();  // Prevent the form from being submitted if the user cancels
        //     }
        // });
    });
</script>
@endsection
