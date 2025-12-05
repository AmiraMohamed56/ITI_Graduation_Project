@extends('layouts.admin')

@section('title','Appointments')
@section('breadcrumb')
    <a href="{{ route('admin.appointments.index') }}" class="hover:underline">Appointments</a>
@endsection

@section('content')
<x-admin.card>
    <div class="flex justify-between items-center mb-10">
        <h2 class="text-lg font-semibold">All Appointments</h2>

        <a href="{{ route('admin.appointments.create') }}">
            <x-admin.button type="secondary">Create Appointment</x-admin.button>
        </a>
    </div>
    <div class="flex justify-between items-center mb-10">
        <div></div>

        <form method="GET">
            @csrf
            <div class="flex items-center space-x-2">
                {{-- <input type="number" name="id"> --}}

                <select name="type"
                id="type"
                class="px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                    <option value="">All types</option>
                    <option value="consultation">Consultation</option>
                    <option value="follow_up">Follow_up</option>
                    <option value="telemedicine">Telemedicine</option>
                </select>

                <input type="date" name="date_from" placeholder="date from" class="px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">

                <input type="date" name="date_to" class="px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">

                <input name="q" value="{{ request('q') }}" placeholder="Search patient or doctor" class="px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400" />
                {{-- <x-admin.input label="" name="q" value="{{ request('q') }}" placeholder="Seach patient or doctor"/> --}}

                <select name="status" class="px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                    <option value="">All status</option>
                    <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status')=='confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>Completed</option>
                </select>
                <x-admin.button>Filter</x-admin.button>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <x-admin.table>
            <thead>
                <tr class="text-left">
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Patient</th>
                    <th class="px-4 py-2">Doctor</th>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Time</th>
                    <th class="px-4 py-2">Type</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Price</th>
                    <th class="px-4 py-2">Notes</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appointment)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $appointment->id }}</td>
                        <td class="px-4 py-3">{{ $appointment->patient->user->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $appointment->doctor->user->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $appointment->schedule_date->format('Y-m-d') }}</td>
                        {{-- <td class="px-4 py-3">{{ \Carbon\Carbon::createFromFormat('H:i:s',$appointment->schedule_time)->format('H:i') }}</td> --}}
                        <td class="px-4 py-3">{{ $appointment->schedule_time }}</td>
                        <td class="px-4 py-3">{{ ucfirst($appointment->type) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-lg text-xs shadow {{
                                $appointment->status === 'pending' ? 'bg-amber-100 text-amber-800' :
                                ($appointment->status === 'confirmed' ? 'bg-emerald-100 text-emerald-800' :
                                ($appointment->status === 'cancelled' ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-800')) }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $appointment->price ? number_format($appointment->price,2) : '-' }}</td>
                        <td class="px-4 py-3">{{ \Illuminate\Support\Str::limit($appointment->notes, 30) }}</td>
                        <td class="px-4 py-3">
                            {{-- <a href="{{ route('admin.appointments.show', $appointment) }}" class="text-sm">View</a> --}}
                            <a href="{{ route('admin.appointments.show', $appointment) }}" class="hover:underline">View</a>
                            {{-- <a href="{{ route('admin.appointments.show', $appointment) }}" class="text-gray-400 hover:text-gray-600"><span class="material-icons text-base">visibility</span></a> --}}
                            {{-- <a href="#" class="text-gray-400 hover:text-gray-600"><span class="material-icons text-base">edit</span></a> --}}
                            {{-- <a href="#" class="text-gray-400 hover:text-gray-600"><span class="material-icons text-base">delete</span></a> --}}
                            {{-- Optionally add Confirm / Cancel actions with forms --}}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="10" class="px-4 py-6 text-center">No appointments found.</td></tr>
                @endforelse
            </tbody>
        </x-admin.table>
    </div>

    <div class="mt-4">
        {{ $appointments->links() }}
    </div>
</x-admin.card>
@endsection
