@extends('Doctors_Dashboard.layouts.app')

@section('title', 'Reviews Management')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Reviews Management</h1>
        <p class="text-gray-600">Manage all patient reviews in one place</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <a href="{{ route('reviews.index', ['filter' => 'all']) }}"
                   class="py-4 px-6 text-center border-b-2 font-medium text-sm {{ $filter === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    All Reviews
                    <span class="ml-2 bg-gray-100 text-gray-800 py-1 px-2.5 rounded-full text-xs font-semibold">
                        {{ $counts['all'] }}
                    </span>
                </a>
                <a href="{{ route('reviews.index', ['filter' => 'pending']) }}"
                   class="py-4 px-6 text-center border-b-2 font-medium text-sm {{ $filter === 'pending' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Pending
                    <span class="ml-2 bg-yellow-100 text-yellow-800 py-1 px-2.5 rounded-full text-xs font-semibold">
                        {{ $counts['pending'] }}
                    </span>
                </a>
                <a href="{{ route('reviews.index', ['filter' => 'approved']) }}"
                   class="py-4 px-6 text-center border-b-2 font-medium text-sm {{ $filter === 'approved' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Approved
                    <span class="ml-2 bg-green-100 text-green-800 py-1 px-2.5 rounded-full text-xs font-semibold">
                        {{ $counts['approved'] }}
                    </span>
                </a>
                <a href="{{ route('reviews.index', ['filter' => 'rejected']) }}"
                   class="py-4 px-6 text-center border-b-2 font-medium text-sm {{ $filter === 'rejected' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Rejected
                    <span class="ml-2 bg-red-100 text-red-800 py-1 px-2.5 rounded-full text-xs font-semibold">
                        {{ $counts['rejected'] }}
                    </span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Reviews List -->
    @if($reviews->isEmpty())
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-star text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-500 text-lg font-medium mb-2">No {{ $filter === 'all' ? '' : $filter }} reviews yet</p>
            <p class="text-gray-400 text-sm">Reviews from patients will appear here</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($reviews as $review)
                <div class="bg-white rounded-lg shadow hover:shadow-md transition-shadow p-6">
                    <div class="flex items-start space-x-4">
                        <!-- Patient Avatar -->
                        <img src="{{ $review->patient->user->profile_picture_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->patient->user->name) }}"
                             alt="{{ $review->patient->user->name }}"
                             class="w-14 h-14 rounded-full border-2 border-gray-200">

                        <!-- Review Content -->
                        <div class="flex-1">
                            <!-- Header with Name and Status -->
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h3 class="font-semibold text-gray-900 text-lg">{{ $review->patient->user->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $review->created_at->format('M d, Y - h:i A') }}</p>
                                </div>

                                <!-- Status Badge -->
                                @if($review->status === 'approved')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Approved
                                    </span>
                                @elseif($review->status === 'rejected')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i> Rejected
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i> Pending
                                    </span>
                                @endif
                            </div>

                            <!-- Rating Stars -->
                            <div class="flex items-center mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }} text-lg"></i>
                                @endfor
                                <span class="ml-2 text-sm font-medium text-gray-700">{{ $review->rating }}/5</span>
                            </div>

                            <!-- Comment -->
                            @if($review->comment)
                                <p class="text-gray-700 leading-relaxed mb-4">{{ $review->comment }}</p>
                            @else
                                <p class="text-gray-400 italic mb-4">No comment provided</p>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex items-center space-x-3">
                                @if($review->status === 'pending')
                                    <!-- Approve Button -->
                                    <form action="{{ route('doctor.reviews.approve', $review->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                onclick="return confirm('Are you sure you want to approve this review?')"
                                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition">
                                            <i class="fas fa-check mr-2"></i> Approve
                                        </button>
                                    </form>

                                    <!-- Reject Button -->
                                    <form action="{{ route('doctor.reviews.reject', $review->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                onclick="return confirm('Are you sure you want to reject this review?')"
                                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition">
                                            <i class="fas fa-times mr-2"></i> Reject
                                        </button>
                                    </form>
                                @elseif($review->status === 'approved')
                                    <!-- Change to Reject -->
                                    <form action="{{ route('doctor.reviews.reject', $review->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                onclick="return confirm('Change status to rejected?')"
                                                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition">
                                            <i class="fas fa-times mr-2"></i> Change to Reject
                                        </button>
                                    </form>
                                @elseif($review->status === 'rejected')
                                    <!-- Change to Approve -->
                                    <form action="{{ route('doctor.reviews.approve', $review->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                onclick="return confirm('Change status to approved?')"
                                                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition">
                                            <i class="fas fa-check mr-2"></i> Change to Approve
                                        </button>
                                    </form>
                                @endif

                                <!-- Delete Button (always available) -->
                                <form action="{{ route('doctor.reviews.destroy', $review->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Are you sure you want to delete this review permanently?')"
                                            class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg transition">
                                        <i class="fas fa-trash mr-2"></i> Delete
                                    </button>
                                </form>
                            </div>

                            <!-- Reviewed At Info -->
                            @if($review->reviewed_at)
                                <p class="text-xs text-gray-500 mt-3">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Status changed {{ $review->reviewed_at->diffForHumans() }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
@endsection
