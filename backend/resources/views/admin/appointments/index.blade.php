@extends('layouts.admin')

@section('title', 'Appointments')

@section('breadcrumb')
    <a href="{{ route('admin.appointments.index') }}" class="hover:underline">Appointments</a>
@endsection

@section('content')

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

    <div class="max-w-7xl mx-auto py-10 px-6">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">

            <div class="flex items-center gap-2">
                <h1 class="text-xl font-semibold">All Appointments</h1>
                <span class="bg-blue-600 text-white text-sm font-medium px-3 py-1 rounded">
                    {{ $appointments->total() }}
                </span>
            </div>

            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                <span>⇅ Sort By :</span>
                <select 
                    onchange="window.location.href='?sort='+this.value"
                    class="border-none bg-transparent cursor-pointer text-gray-600 dark:text-gray-300">
                    <option value="newest" {{ request('sort')=='newest' ? 'selected' : '' }}>Newest</option>
                    <option value="oldest" {{ request('sort')=='oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="date" {{ request('sort')=='date' ? 'selected' : '' }}>Date</option>
                    <option value="status" {{ request('sort')=='status' ? 'selected' : '' }}>Status</option>
                </select>
            </div>

            <a href="{{ route('admin.appointments.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium">
                Create Appointment
            </a>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <x-admin.alert type="success" :message="session('success')" />
        @endif

        @if(session('error'))
            <x-admin.alert type="error" :message="session('error')" />
        @endif


        <!-- Filters -->
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">

                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium mb-1">Type</label>
                    <select name="type"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 text-sm">
                        <option value="">All types</option>
                        <option value="consultation" {{ request('type')=='consultation'?'selected':'' }}>Consultation</option>
                        <option value="follow_up" {{ request('type')=='follow_up'?'selected':'' }}>Follow Up</option>
                        <option value="telemedicine" {{ request('type')=='telemedicine'?'selected':'' }}>Telemedicine</option>
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium mb-1">Date From</label>
                    <input type="date" name="date_from"
                        value="{{ request('date_from') }}"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 text-sm">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium mb-1">Date To</label>
                    <input type="date" name="date_to"
                        value="{{ request('date_to') }}"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 text-sm">
                </div>

                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium mb-1">Patient or Doctor</label>
                    <input type="text" name="q" placeholder="Search..."
                        value="{{ request('q') }}"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 text-sm">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <select name="status"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 text-sm">
                        <option value="">All status</option>
                        <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                        <option value="confirmed" {{ request('status')=='confirmed'?'selected':'' }}>Confirmed</option>
                        <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Cancelled</option>
                        <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Completed</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                        Filter
                    </button>
                    <a href="{{ route('admin.appointments.index') }}"
                        class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 text-sm font-medium text-center">
                        Clear
                    </a>
                </div>

            </form>
        </div>


        <!-- Table -->
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow">
            <table class="w-full text-left">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-sm font-medium">ID</th>
                        <th class="px-6 py-4 text-sm font-medium">Patient</th>
                        <th class="px-6 py-4 text-sm font-medium">Doctor</th>
                        <th class="px-6 py-4 text-sm font-medium">Date</th>
                        <th class="px-6 py-4 text-sm font-medium">Time</th>
                        <th class="px-6 py-4 text-sm font-medium">Type</th>
                        <th class="px-6 py-4 text-sm font-medium">Status</th>
                        <th class="px-6 py-4 text-sm font-medium">Price</th>
                        <th class="px-6 py-4 text-sm font-medium">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($appointments as $appointment)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">

                        <td class="px-6 py-4 text-sm">#{{ $appointment->id }}</td>

                        <td class="px-6 py-4 text-sm">
                            {{ $appointment->patient->user->name ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-sm">
                            {{ $appointment->doctor->user->name ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-sm">{{ $appointment->schedule_date->format('Y-m-d') }}</td>

                        <td class="px-6 py-4 text-sm">{{ $appointment->schedule_time }}</td>

                        <td class="px-6 py-4 text-sm">{{ ucfirst($appointment->type) }}</td>

                        <td class="px-6 py-4 text-sm">
                            <span class="px-3 py-1 rounded-lg text-xs shadow
                                {{ $appointment->status=='pending' ? 'bg-amber-100 text-amber-800' :
                                   ($appointment->status=='confirmed' ? 'bg-emerald-100 text-emerald-800' :
                                   ($appointment->status=='cancelled' ? 'bg-rose-100 text-rose-800' :
                                   'bg-slate-100 text-slate-800')) }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-sm">
                            {{ $appointment->price ? number_format($appointment->price,2) : '-' }}
                        </td>

                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('admin.appointments.show', $appointment) }}"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <span class="material-icons text-base">visibility</span>
                            </a>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-6 text-gray-500 dark:text-gray-400">
                            No appointments found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $appointments->links() }}
        </div>

    </div>
</div>

@endsection
