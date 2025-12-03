@extends('Doctors_Dashboard.layouts.app')

@section('title', 'Medical Records')

@section('content')
  <!-- Header -->
  <div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Medical Records</h1>
    <div class="flex items-center text-sm text-gray-500 mt-2">
      <span>Home</span>
      <span class="mx-2">»</span>
      <span>Medical Records</span>
    </div>
  </div>

  <div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="p-6 border-b border-gray-200">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
          <h2 class="text-xl font-semibold text-gray-900">Total Medical Records</h2>
          <span class="bg-red-600 text-white text-xs font-bold px-2.5 py-1 rounded">
            {{ $medicalRecords->total() }}
          </span>
        </div>

        <div class="flex gap-2">
        <a href="{{ route('medical_records.create') }}"
           class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
          <i class="fas fa-plus"></i>
          Create Medical Record
        </a>
         {{-- <a href="{{ route('medical_records.create') }}"
           class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
          Deleted Records
        </a> --}}
        </div>
      </div>

      <!-- Search Row -->
      <div class="flex items-center gap-3">
        <!-- Search Bar -->
        <form method="GET" action="{{  route('medical_records.index') }}" class="relative flex-1">
          <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search by patient name, symptoms, diagnosis..."
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          />
          <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
          <button type="submit" class="hidden">Search</button>
        </form>

        @if(request('search'))
        <a href="{{  route('medical_records.index') }}"
           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition-colors">
          Clear
        </a>
        @endif
      </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Patient Name</th>            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Symptoms</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Diagnosis</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date</th>
            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($medicalRecords as $record)
          <tr class="hover:bg-gray-50 transition-colors">


            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center gap-3">
                <span class="text-sm font-medium text-gray-900">{{ $record->patient->user->name }}</span>
              </div>
            </td>


            <td class="px-6 py-4">
              <span class="text-sm text-gray-600">
                {{ Str::limit($record->symptoms ?? 'N/A', 30) }}
              </span>
            </td>

            <td class="px-6 py-4">
              <span class="text-sm text-gray-600">
                {{ Str::limit($record->diagnosis ?? 'N/A', 30) }}
              </span>
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              <span class="text-sm text-gray-600">
                {{ $record->created_at->format('d M Y, h:i A') }}
              </span>
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center justify-center gap-2">
                <a href="{{ route('medical_records.show', $record->id)  }}"
                   class="inline-flex items-center justify-center w-8 h-8 rounded bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors"
                   title="View Details">
                  <i class="fas fa-eye text-sm"></i>
                </a>
                <a href="{{ route('medical_records.edit', $record->id) }}"
                   class="inline-flex items-center justify-center w-8 h-8 rounded bg-green-100 text-green-600 hover:bg-green-200 transition-colors"
                   title="Edit">
                  <i class="fas fa-edit text-sm"></i>
                </a>
                <form action="{{ route('medical_records.destroy', $record->id)  }}"
                      method="POST"
                      class="inline-block"
                      onsubmit="return confirm('Are you sure you want to delete this medical record?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                          class="inline-flex items-center justify-center w-8 h-8 rounded bg-red-100 text-red-600 hover:bg-red-200 transition-colors"
                          title="Delete">
                    <i class="fas fa-trash text-sm"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
              <div class="flex flex-col items-center justify-center">
                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                <p class="text-lg font-medium">No medical records found</p>
                @if(request('search'))
                <p class="text-sm mt-1">Try adjusting your search terms</p>
                @endif
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200">
      <div class="text-sm text-gray-600">
        Showing {{ $medicalRecords->firstItem() ?? 0 }} to {{ $medicalRecords->lastItem() ?? 0 }} of {{ $medicalRecords->total() }} entries
      </div>

      <div class="flex items-center gap-2">
        {{ $medicalRecords->links() }}
      </div>
    </div>
  </div>

  @if(session('success'))
  <script>
    alert('{{ session('success') }}');
  </script>
  @endif
@endsection
