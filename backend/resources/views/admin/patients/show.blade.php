@extends('admin.layout')

@section('title', 'Patient Details')

@section('content')
<div class="bg-white p-6 shadow rounded mb-6">
    <h2 class="text-3xl font-bold mb-4">Patient Details</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p><strong>Name:</strong> {{ $patient->user->name }}</p>
            <p><strong>Email:</strong> {{ $patient->user->email }}</p>
            <p><strong>Phone:</strong> {{ $patient->user->phone }}</p>
        </div>
        <div>
            <p><strong>Blood Type:</strong> {{ $patient->blood_type }}</p>
            <p><strong>Chronic Diseases:</strong> {{ $patient->chronic_diseases ?: 'None' }}</p>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.patients.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">
            Back to List
        </a>
    </div>
</div>

{{-- Appointments Table --}}
<div class="bg-white p-6 shadow rounded">
    <h3 class="text-2xl font-bold mb-4">Appointments</h3>

    @if($appointments->isEmpty())
    <p class="text-gray-500">No appointments found for this patient.</p>
    @else
    <table class="w-full border-collapse border border-gray-200">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-3 border">#</th>
                <th class="p-3 border">Doctor</th>
                <th class="p-3 border">Date</th>
                <th class="p-3 border">Time</th>
                <th class="p-3 border">Type</th>
                <th class="p-3 border">Status</th>
                <th class="p-3 border">Price</th>
                <th class="p-3 border">Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appointment)
            <tr class="border-b">
                <td class="p-3 border">{{ $appointment->id }}</td>
                <td class="p-3 border">{{ $appointment->doctor->user->name ?? 'N/A' }}</td>
                <td class="p-3 border">{{ $appointment->schedule_date->format('Y-m-d') }}</td>
                <td class="p-3 border">{{ $appointment->schedule_time->format('H:i') }}</td>
                <td class="p-3 border">{{ ucfirst($appointment->type) }}</td>
                <td class="p-3 border">{{ ucfirst($appointment->status) }}</td>
                <td class="p-3 border">${{ number_format($appointment->price, 2) }}</td>
                <td class="p-3 border">{{ $appointment->notes ?: '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
