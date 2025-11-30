@extends('layouts.admin')
@section('title', 'Completed Visits')

@section('content')
<div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-semibold text-gray-900">Total Visits</h1>
                <span class="bg-red-500 text-white text-sm font-semibold px-3 py-1 rounded-md">
                    {{ $visits->total() }}
                </span>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span>⇅ Sort By :</span>
                    <select onchange="window.location.href='?sort='+this.value"
                        class="border-none bg-transparent cursor-pointer">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    </select>
                </div>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Visit ID
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Patient Name
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Department
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Doctor Name
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Visit Date
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($visits as $visit)
                        <tr class="hover:bg-gray-50 transition-colors">

                            {{-- Visit ID --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $visit->id }}
                            </td>

                            {{-- Patient --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.patients.show', $visit->patient->id) }}"
                                   class="flex items-center gap-3">
                                    <img src="{{ asset('images/patient.jpg') }}" alt="Patient"
                                        class="w-10 h-10 rounded-full object-cover">
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $visit->patient->user->name }}
                                    </span>
                                </a>
                            </td>

                            {{-- Specialty --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $visit->doctor->specialty->name }}
                            </td>

                            {{-- Doctor --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.doctors.show', $visit->doctor->id) }}"
                                   class="flex items-center gap-3">
                                    <img src="{{ asset('images/doctor.jpg') }}" alt="Doctor"
                                        class="w-10 h-10 rounded-full object-cover">
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $visit->doctor->user->name }}
                                    </span>
                                </a>
                            </td>

                            {{-- Date --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($visit->schedule_date)->format('Y-m-d') }}
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                    Completed
                                </span>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $visits->links() }}
        </div>

    </div>
</div>
@endsection
