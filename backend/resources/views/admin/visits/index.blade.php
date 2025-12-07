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
                        @foreach ($visits as $visit)
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
                                            $name = $visit->patient->user->name;
                                            $initial = strtoupper(substr($name, 0, 1));
                                        @endphp
                                        <div
                                            class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white text-lg font-bold">
                                            {{ $initial }}
                                        </div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $name }}
                                        </span>
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
                                            $name = $visit->doctor->user->name;
                                            $initial = strtoupper(substr($name, 0, 1));
                                        @endphp
                                        <div
                                            class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white text-lg font-bold">
                                            {{ $initial }}
                                        </div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $name }}
                                        </span>
                                    </a>
                                </td>

                                {{-- Date --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($visit->schedule_date)->format('Y-m-d') }}
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-3 py-1 text-xs font-semibold rounded-full 
                                    bg-green-100 dark:bg-green-900 
                                    text-green-700 dark:text-green-200">
                                        Completed
                                    </span>
                                </td>

                            </tr>
                        @endforeach
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
