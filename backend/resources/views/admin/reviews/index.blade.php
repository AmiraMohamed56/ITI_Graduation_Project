@extends('layouts.admin')

@section('title', 'Reviews Management')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Reviews Management</h1>
        <p class="text-gray-600 dark:text-gray-400">Manage all patient reviews in one place</p>
    </div>

    <!-- Enhanced Alert Messages -->
    @if(session('success'))
        <div class="mb-6 bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border-l-4 border-green-500 rounded-lg shadow-sm animate-fade-in">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                    </div>
                </div>
                <button onclick="this.closest('.animate-fade-in').style.display='none'" 
                        class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border-l-4 border-red-500 rounded-lg shadow-sm animate-fade-in">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
                    </div>
                </div>
                <button onclick="this.closest('.animate-fade-in').style.display='none'" 
                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="mb-6 bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 border-l-4 border-yellow-500 rounded-lg shadow-sm animate-fade-in">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">{{ session('warning') }}</p>
                    </div>
                </div>
                <button onclick="this.closest('.animate-fade-in').style.display='none'" 
                        class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-200 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Search Bar -->
    <div class="mb-6">
        <form method="GET" class="max-w-md">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search by patient name ..." 
                       class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white">
                <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                @if(request('search'))
                    <a href="{{ route('admin.reviews.index', ['filter' => request('filter')]) }}" 
                       class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fas fa-times-circle"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Reviews Table -->
    @if($reviews->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
            <i class="fas fa-star text-gray-300 dark:text-gray-600 text-6xl mb-4"></i>
            <p class="text-gray-500 dark:text-gray-400 text-lg font-medium mb-2">
                No {{ $filter === 'all' ? '' : $filter }} reviews {{ request('search') ? 'matching your search' : 'yet' }}
            </p>
            <p class="text-gray-400 dark:text-gray-500 text-sm">Reviews from patients will appear here</p>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Patient
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Doctor
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Rating
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Comment
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($reviews as $review)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <!-- Patient Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img src="{{ $review->patient->user->profile_picture_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->patient->user->name) . '&background=6366f1&color=fff' }}"
                                             alt="{{ $review->patient->user->name }}"
                                             class="w-10 h-10 rounded-full border-2 border-gray-200 dark:border-gray-600">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $review->patient->user->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $review->patient->user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Doctor Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img src="{{ $review->doctor->user->profile_picture_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->doctor->user->name) . '&background=10b981&color=fff' }}"
                                             alt="{{ $review->doctor->user->name }}"
                                             class="w-10 h-10 rounded-full border-2 border-gray-200 dark:border-gray-600">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                Dr. {{ $review->doctor->user->name }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $review->doctor->specialization ?? 'General' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Rating Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }} text-sm"></i>
                                        @endfor
                                    </div>
                                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mt-1">
                                        {{ $review->rating }}/5
                                    </div>
                                </td>

                                <!-- Comment Column -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-300 max-w-md">
                                        @if($review->comment)
                                            <p class="line-clamp-2">{{ $review->comment }}</p>
                                        @else
                                            <p class="text-gray-400 dark:text-gray-500 italic">No comment</p>
                                        @endif
                                    </div>
                                </td>

                                <!-- Date Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-300">
                                        {{ $review->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $review->created_at->format('h:i A') }}
                                    </div>
                                </td>

                                <!-- Actions Column -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.reviews.show', $review) }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </a>
                                        <button type="button"
                                                onclick="openDeleteModal({{ $review->id }}, '{{ $review->patient->user->name }}', '{{ $review->doctor->user->name }}')"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md transition">
                                            <i class="fas fa-trash mr-1"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $reviews->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80" 
             aria-hidden="true" 
             onclick="closeDeleteModal()"></div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-gray-800 rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="px-4 pt-5 pb-4 bg-white dark:bg-gray-800 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <!-- Icon -->
                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 dark:bg-red-900/30 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-lg"></i>
                    </div>
                    <!-- Content -->
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white" id="modal-title">
                            Delete Review
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Are you sure you want to delete the review from <span id="modalPatientName" class="font-semibold text-gray-900 dark:text-white"></span> 
                                about <span id="modalDoctorName" class="font-semibold text-gray-900 dark:text-white"></span>?
                            </p>
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 font-medium">
                                This action cannot be undone.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Actions -->
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-900 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition">
                        <i class="fas fa-trash mr-2"></i>
                        Delete Review
                    </button>
                </form>
                <button type="button"
                        onclick="closeDeleteModal()"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm transition">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
</style>

<script>
    function openDeleteModal(reviewId, patientName, doctorName) {
        document.getElementById('modalPatientName').textContent = patientName;
        document.getElementById('modalDoctorName').textContent = 'Dr. ' + doctorName;
        document.getElementById('deleteForm').action = `/admin/reviews/${reviewId}`;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>
@endsection