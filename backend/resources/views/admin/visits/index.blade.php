@extends('layouts.admin')
@section('title', 'Completed Visits')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-8 text-gray-900 dark:text-gray-100">
        <div class="max-w-7xl mx-auto">

            {{-- Header --}}
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Total Visits</h1>
                    <span class="bg-red-500 text-white text-sm font-semibold px-3 py-1 rounded-md">
                        {{ $visits->total() }}
                    </span>
                </div>

                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                    <span>⇅ Sort By :</span>
                    <select onchange="window.location.href='?sort='+this.value"
                        class="border-none bg-transparent cursor-pointer text-gray-900 dark:text-gray-100">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    </select>
                </div>
            </div>

            {{-- Filters --}}
            <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <form method="GET" action="{{ route('admin.visits.index') }}"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

                    <!-- Patient Name Filter -->
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Patient Name</label>
                        <input type="text" name="patient_name" placeholder="Search by patient..."
                            value="{{ request('patient_name') }}"
                            class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm" />
                    </div>

                    <!-- Doctor Name Filter -->
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Doctor Name</label>
                        <input type="text" name="doctor_name" placeholder="Search by doctor..."
                            value="{{ request('doctor_name') }}"
                            class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm" />
                    </div>

                    <div class="md:col-span-1"></div>

                    <!-- Action Buttons -->
                    <div class="flex md:col-span-1 justify-end gap-2">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-filter text-xs"></i>
                            Filter
                        </button>

                        <a href="{{ route('admin.visits.index') }}"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 text-sm font-medium transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-times text-xs"></i>
                            Clear
                        </a>
                    </div>

                </form>
            </div>

            {{-- Table --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-200 dark:border-gray-700">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Visit ID
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Patient Name
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Specialty
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Doctor Name
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Visit Date
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-4"></th>
                        </tr>
                    </thead>

                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">

                        @forelse ($visits as $visit)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">

                                {{-- Visit ID --}}
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $visit->id }}
                                </td>

                                {{-- Patient --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.patients.show', $visit->patient->id) }}"
                                        class="flex items-center gap-3">

                                        @php
                                            $image = $visit->patient->user->profile_pic ?? null;
                                            $name = $visit->patient->user->name ?? 'User';
                                            $initial = strtoupper(substr($name, 0, 1));

                                            $imageFile = $image ? basename($image) : null;
                                            $imagePath = $imageFile
                                                ? asset('storage/profile_pics/' . $imageFile)
                                                : null;
                                        @endphp

                                        @if ($imagePath && file_exists(public_path('storage/profile_pics/' . $imageFile)))
                                            <img src="{{ $imagePath }}" class="w-10 h-10 rounded-full object-cover"
                                                alt="{{ $name }}">
                                        @else
                                            <div
                                                class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white text-lg font-bold">
                                                {{ $initial }}
                                            </div>
                                        @endif

                                        <span
                                            class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $name }}</span>

                                    </a>
                                </td>


                                {{-- Specialty --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $visit->doctor->specialty->name }}
                                </td>

                                {{-- Doctor --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.doctors.show', $visit->doctor->id) }}"
                                        class="flex items-center gap-3">

                                        @php
                                            $image = $visit->doctor->user->profile_pic ?? null;
                                            $name = $visit->doctor->user->name ?? 'Doctor';
                                            $initial = strtoupper(substr($name, 0, 1));

                                            $imageFile = $image ? basename($image) : null;
                                            $imagePath = $imageFile
                                                ? asset('storage/profile_pictures/' . $imageFile)
                                                : null;
                                        @endphp

                                        @if ($imagePath && file_exists(public_path('storage/profile_pictures/' . $imageFile)))
                                            <img src="{{ $imagePath }}" class="w-10 h-10 rounded-full object-cover"
                                                alt="{{ $name }}">
                                        @else
                                            <div
                                                class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white text-lg font-bold">
                                                {{ $initial }}
                                            </div>
                                        @endif

                                        <span
                                            class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $name }}</span>

                                    </a>
                                </td>


                                {{-- Date --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($visit->schedule_date)->format('Y-m-d') }}
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200">
                                        Completed
                                    </span>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 text-center text-gray-600 dark:text-gray-300 text-sm">
                                    No Data Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4 text-gray-900 dark:text-gray-100">
                {{ $visits->links() }}
            </div>

        </div>
    </div>
@endsection
