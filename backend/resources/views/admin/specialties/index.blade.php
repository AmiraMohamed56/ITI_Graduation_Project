@extends('layouts.admin')

@section('title', 'Specialties')

@section('breadcrumb')
    <a href="{{ route('admin.specialties.index') }}" class="hover:underline">Specialties</a>
@endsection


@section('content')

@if(session('success'))
    <x-admin.alert type="success" :message="session('success')" />
@endif

@if(session('error'))
    <x-admin.alert type="error" :message="session('error')" />
@endif

<x-admin.card>
    <div class="flex justify-between items-center mb-10">
        <h2 class="text-lg font-semibold">All Specialties</h2>

        <a href="{{ route('admin.specialties.create') }}">
            <x-admin.button type="secondary">Create Specialty</x-admin.button>
        </a>
    </div>

    {{-- Filter Form --}}
    <div class="flex items-center justify-between">
        <div></div>
        <form method="GET" action="{{ route('admin.specialties.index') }}" class="mb-6 flex items-center space-x-2">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name..."
                class="px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"
            />

            <x-admin.button type="primary" >Search</x-admin.button>
            @if(request('search'))
                <x-admin.button type="secondary">
                    <a href="{{ route('admin.specialties.index') }}" >Clear</a>
                </x-admin.button>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <x-admin.table>
            <thead>
                <tr class="text-center">
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Name</th>
                    {{-- <th class="px-4 py-2">Actions</th> --}}
                </tr>
            </thead>
            <tbody>
                @forelse($specialties as $s)
                    <tr class="text-center border-t">
                        <td class="px-4 py-3">{{ $s->id }}</td>
                        <td class="px-4 py-3"><a href="{{ route('admin.specialties.show', $s) }}">{{ $s->name ?? '-' }}</a></td>
                        {{-- <td class="px-4 py-3">
                            <a href="{{ route('admin.specialties.show', $s) }}" class="hover:underline">View</a>
                        </td> --}}

                    </tr>
                @empty
                    <tr><td colspan="2" class="px-4 py-6 text-center">No specialties found.</td></tr>
                @endforelse
            </tbody>
        </x-admin.table>
    </div>

    <div class="mt-4">
        {{ $specialties->links() }}
    </div>
</x-admin.card>
@endsection

