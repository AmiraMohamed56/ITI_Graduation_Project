@extends('layouts.admin')

@section('title', 'Review Details')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border-l-4 border-green-500 rounded-lg shadow-sm animate-slideIn">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                    </div>
                </div>
                <button onclick="this.closest('.animate-slideIn').style.animation = 'slideOut 0.3s ease-out forwards'; setTimeout(() => this.closest('.bg-gradient-to-r').remove(), 300)" 
                        class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Review Details</h1>
                    <p class="mt-1 opacity-90">Review ID: #{{ $review->id }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star text-2xl {{ $i <= $review->rating ? 'text-yellow-300' : 'text-white/30' }}"></i>
                    @endfor
                </div>
            </div>
        </div>

        <div class="p-8">
            <!-- Patient and Doctor Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Patient Card -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-6 rounded-xl border border-blue-100 dark:border-blue-800">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center">
                        <i class="fas fa-user-circle mr-2 text-blue-600 dark:text-blue-400"></i>
                        Patient Information
                    </h3>
                    <div class="flex items-center gap-4">
                        <img src="{{ $review->patient->user->profile_picture_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->patient->user->name) . '&background=6366f1&color=fff&size=128' }}"
                             alt="{{ $review->patient->user->name }}"
                             class="w-16 h-16 rounded-full border-4 border-white dark:border-gray-700 shadow-lg">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 dark:text-white text-lg">{{ $review->patient->user->name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center mt-1">
                                <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                {{ $review->patient->user->email }}
                            </p>
                            @if($review->patient->phone)
                                <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center mt-1">
                                    <i class="fas fa-phone mr-2 text-gray-400"></i>
                                    {{ $review->patient->phone }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Doctor Card -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 p-6 rounded-xl border border-green-100 dark:border-green-800">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center">
                        <i class="fas fa-user-md mr-2 text-green-600 dark:text-green-400"></i>
                        Reviewed Doctor
                    </h3>
                    <div class="flex items-center gap-4">
                        <img src="{{ $review->doctor->user->profile_picture_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->doctor->user->name) . '&background=10b981&color=fff&size=128' }}"
                             alt="{{ $review->doctor->user->name }}"
                             class="w-16 h-16 rounded-full border-4 border-white dark:border-gray-700 shadow-lg">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 dark:text-white text-lg">Dr. {{ $review->doctor->user->name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center mt-1">
                                <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                {{ $review->doctor->user->email ?? 'N/A' }}
                            </p>
                            @if($review->doctor->specialization)
                                <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center mt-1">
                                    <i class="fas fa-stethoscope mr-2 text-gray-400"></i>
                                    {{ $review->doctor->specialization }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rating Section -->
            <div class="mb-8 bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20 p-6 rounded-xl border border-yellow-100 dark:border-yellow-800">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                    <i class="fas fa-star mr-2 text-yellow-600 dark:text-yellow-400"></i>
                    Rating
                </h3>
                <div class="flex items-center gap-3">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star text-3xl {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}"></i>
                    @endfor
                    <span class="ml-3 text-2xl font-bold text-gray-900 dark:text-white">{{ $review->rating }}<span class="text-gray-400">/5</span></span>
                </div>
            </div>

            <!-- Comment Section -->
            @if($review->comment)
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                    <i class="fas fa-comment-alt mr-2 text-gray-600 dark:text-gray-400"></i>
                    Patient Comment
                </h3>
                <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-xl border border-gray-200 dark:border-gray-600">
                    <p class="text-gray-800 dark:text-gray-200 leading-relaxed whitespace-pre-line">{{ $review->comment }}</p>
                </div>
            </div>
            @else
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                    <i class="fas fa-comment-alt mr-2 text-gray-600 dark:text-gray-400"></i>
                    Patient Comment
                </h3>
                <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-xl border border-gray-200 dark:border-gray-600 text-center">
                    <i class="fas fa-comment-slash text-gray-300 dark:text-gray-600 text-4xl mb-2"></i>
                    <p class="text-gray-400 dark:text-gray-500 italic">No comment provided</p>
                </div>
            </div>
            @endif

            <!-- Metadata -->
            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600 mb-6">
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                        <i class="fas fa-clock mr-2"></i>
                        <span>Submitted on: <span class="font-medium text-gray-900 dark:text-white">{{ $review->created_at->format('F d, Y \a\t h:i A') }}</span></span>
                    </div>
                    @if($review->updated_at != $review->created_at)
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <i class="fas fa-edit mr-2"></i>
                            <span>Last updated: <span class="font-medium text-gray-900 dark:text-white">{{ $review->updated_at->format('F d, Y \a\t h:i A') }}</span></span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.reviews.index') }}" 
                   class="inline-flex items-center px-6 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Reviews
                </a>
                
                <button type="button"
                        onclick="openDeleteModal()"
                        class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-all shadow-lg hover:shadow-xl">
                    <i class="fas fa-trash-alt mr-2"></i>
                    Delete Review
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background overlay -->
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 backdrop-blur-sm" 
             aria-hidden="true" 
             onclick="closeDeleteModal()"></div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-gray-800 rounded-xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-modalSlide">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                <div class="flex items-center">
                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 bg-white/20 rounded-full">
                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                    </div>
                    <h3 class="ml-4 text-xl font-bold text-white" id="modal-title">
                        Confirm Deletion
                    </h3>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-6 bg-white dark:bg-gray-800">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full">
                            <i class="fas fa-trash-alt text-red-600 dark:text-red-400 text-lg"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                            Are you sure you want to permanently delete this review?
                        </p>
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600 mb-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Review Details</span>
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star text-xs {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <span class="font-semibold">From:</span> {{ $review->patient->user->name }}
                            </p>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <span class="font-semibold">About:</span> Dr. {{ $review->doctor->user->name }}
                            </p>
                        </div>
                        <div class="flex items-start space-x-2 bg-red-50 dark:bg-red-900/20 p-3 rounded-lg border border-red-200 dark:border-red-800">
                            <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 mt-0.5"></i>
                            <p class="text-sm text-red-700 dark:text-red-300 font-medium">
                                This action cannot be undone. The review will be permanently removed from the system.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <button type="button"
                        onclick="closeDeleteModal()"
                        class="inline-flex justify-center items-center w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </button>
                <form id="deleteForm" method="POST" action="{{ route('admin.reviews.destroy', $review) }}" class="w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex justify-center items-center w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-lg hover:shadow-xl transition-all">
                        <i class="fas fa-trash-alt mr-2"></i>
                        Delete Permanently
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    @keyframes modalSlide {
        from {
            transform: scale(0.95) translateY(-20px);
            opacity: 0;
        }
        to {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    }

    .animate-slideIn {
        animation: slideIn 0.3s ease-out forwards;
    }

    .animate-modalSlide {
        animation: modalSlide 0.3s ease-out forwards;
    }
</style>

<script>
    function openDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>
@endsection