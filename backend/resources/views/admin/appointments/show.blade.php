
@extends('layouts.admin')

@section('title', 'Appointment Details')

@section('breadcrumb')
    <a href="{{ route('admin.appointments.index') }}" class="hover:underline">Appointments</a> / #{{ $appointment->id }}
@endsection

@section('content')


<div class="mb-6 flex justify-between">
    <div></div>


    <x-admin.button size="sm" type="secondary"><a href="{{ route('admin.appointments.edit', $appointment) }}">edit appointment</a></x-admin.button>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    {{-- Appointment Info --}}
    <x-admin.card class="md:col-span-1 relative">

        <h2 class="text-lg font-semibold mb-4">Appointment Information</h2>


        <div class="space-y-2 text-sm">
            <div><strong>ID: </strong> {{ $appointment->id }}</div>
            <div><strong>Date: </strong> {{ $appointment->schedule_date->format('d-m-Y') }}</div>
            <div><strong>Time: </strong> {{ $appointment->schedule_time->format('H:i:s') }}</div>
            <div><strong>Type: </strong> {{ $appointment->type }}</div>

            <div>
                <strong>Status: </strong>
                <span class="px-2 py-1 rounded-md text-xs {{
                $appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                ($appointment->status === 'confirmed' ? 'bg-green-100 text-green-800' :
                ($appointment->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))
                 }}"> {{ ucfirst($appointment->status) }}</span>
            </div>

            <div><strong>Duration: </strong> {{ $appointment->doctorSchedule->appointment_duration }} minutes</div>
            {{-- <div><strong>Price: </strong> ${{ $appointment->price ? number_format($appointment->price, 2) : '-' }}</div> --}}
            <div><strong>Notes: </strong> {{ $appointment->notes ? : '-' }}</div>

            <div><strong>Created at: </strong> {{ $appointment->created_at->format('d-m-Y H:i:s') }}</div>
            <div><strong>Updated at: </strong> {{ $appointment->updated_at->format('d-m-Y H:i:s') }}</div>

        </div>

    </x-admin.card>

    {{-- Patient Info --}}
    <x-admin.card class="md:col-span-1">
        <h2 class="text-lg font-semibold mb-4">Patient Information</h2>
        <div class="space-y-2 text-sm">
            <div><strong>Name: </strong><a href="{{ route('admin.patients.show', $appointment->patient) }}" class="hover:underline">{{ $appointment->patient->user->name }}</a></div>
            <div><strong>Email: </strong> {{ $appointment->patient->user->email }}</div>
            <div><strong>Phone: </strong> {{ $appointment->patient->user->phone }}</div>
            {{-- <div><strong>Gender: </strong> {{ $appointment->patient->user->gender ?? '-' }}</div> --}}
            {{-- <div><strong>Birth Date: </strong> {{ $appointment->patient->user->birthdate?? '-' }}</div> --}}
            <div><strong>Patient appointments: </strong><a href="{{ route('admin.appointments.index', ['q' => $appointment->patient->user->name]) }}" class="hover:underline">➝</a></div>
            {{-- <div><strong>Medical history: </strong></div> --}}
        </div>
    </x-admin.card>

    {{-- Doctor Info --}}
    <x-admin.card class="md:col-span-1">
        <h2 class="text-lg font-semibold mb-4">Doctor Information</h2>
        <div class="space-y-2 text-sm">
            <div><strong>Name: </strong> <a href="{{ route('admin.doctors.show', $appointment->doctor) }}" class="hover:underline">{{ $appointment->doctor->user->name }}</a></div>
            <div><strong>Email: </strong> {{ $appointment->doctor->user->email }}</div>
            <div><strong>Phone: </strong> {{ $appointment->doctor->user->phone ?? '-' }}</div>
            <div><strong>Specialization: </strong> {{ $appointment->doctor->specialty->name }}</div>
            <div><strong>Doctor appointments: </strong><a href="{{ route('admin.appointments.index', ['q' => $appointment->doctor->user->name]) }} " class="hover:underline">➝</a></div>
        </div>
    </x-admin.card>

    {{-- Payment Info --}}
    <x-admin.card class="md:col-span-1">
        <h2 class="text-lg font-semibold mb-4">Payment Information</h2>

        @if($payment)
        <div class="space-y-2 text-sm">
            <div><strong>Price: </strong> {{ $payment->amount ?? '-' }}</div>
            <div><strong>Payment Method: </strong> {{ $payment->method ?? '-' }}</div>
            @if ($payment)
            <div><strong>Payment Status: </strong> <span class="px-2 py-1 rounded-md text-xs {{
                $payment->status === 'paid' ? 'bg-emerald-100 text-emerald-800' :
                ($payment->status === 'failed' ? 'bg-rose-100 text-rose-800' : 'bg-blue-100 text-blue-800')
                }}">{{ $payment->status ?? '-' }}</span></div>
            @endif
            <div><strong>Transaction_id: </strong> {{ $payment->transaction_id ?? '-' }}</div>
            <div><strong>Created at: </strong> {{ $payment->created_at->format('d-m-Y H:i:s') ?? '-' }}</div>
        </div>
        @else
        <div class="text-sm mb-4">No payment iformation for this appointment.</div>

        <a href="{{ route('admin.payments.create', $appointment) }}">
            <x-admin.button type="secondary" size="sm">Pay</x-admin.button>
        </a>
        @endif
    </x-admin.card>

</div>

<div class="mt-6 flex justify-between">
    {{-- <form action="{{ route('admin.appointments.destroy', $appointment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this appointment?');">
        @csrf
        @method('DELETE')
        <x-admin.button type="danger">Delete Appointment</x-admin.button>
    </form> --}}
    {{-- trigger delete modal --}}
    <x-admin.button type="danger" id="delete-button">Delete appointment</x-admin.button>

    <!-- Confirmation Modal (Initially Hidden) -->
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 shadow rounded p-6 w-96">
            <h2 class="text-xl mb-4">Are you sure you want to delete this payment?</h2>
            <div class="flex justify-end gap-4">
                <!-- Cancel Button -->
                <x-admin.button id="cancel-button" type="secondary" size="sm">Cancel</x-admin.button>

                {{-- delete form --}}
                <form id="delete-form" action="{{ route('admin.appointments.destroy', $appointment->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <x-admin.button type="danger" size="sm">Cofirm</x-admin.button>
                </form>
            </div>
        </div>
    </div>
    <div></div>
</div>

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

    });
</script>
@endsection
