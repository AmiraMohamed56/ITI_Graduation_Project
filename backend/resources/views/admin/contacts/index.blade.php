@extends('layouts.admin')

@section('title', 'Contact Messages')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-md">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Contact Messages</h1>
            <p class="text-gray-600">Manage and respond to customer inquiries</p>
        </div>

        <!-- Messages Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Table Container -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Message</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($contacts as $contact)
                            <tr class="hover:bg-blue-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-800 text-sm font-medium">
                                        {{ $contact->id }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center text-white font-semibold mr-3">
                                            {{ strtoupper(substr($contact->name, 0, 1)) }}
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $contact->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="mailto:{{ $contact->email }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                                        {{ $contact->email }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ $contact->phone }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $contact->subject }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600 max-w-xs">{{ Str::limit($contact->message, 50) }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($contact->replied)
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Replied
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button type="button" 
                                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg" 
                                            onclick="openModal('replyModal{{ $contact->id }}')">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                        </svg>
                                        {{ $contact->replied ? 'Reply Again' : 'Reply' }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $contacts->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Reply Modals (one per contact) -->
@foreach($contacts as $contact)
    <div id="replyModal{{ $contact->id }}" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50" onclick="closeModalOnBackdrop(event, 'replyModal{{ $contact->id }}')">
        <div class="relative top-20 mx-auto max-w-2xl" onclick="event.stopPropagation()">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-bold text-lg">
                                {{ strtoupper(substr($contact->name, 0, 1)) }}
                            </div>
                            <div>
                                <h5 class="text-xl font-bold text-white">
                                    Reply to {{ $contact->name }}
                                </h5>
                                <p class="text-blue-100 text-sm">{{ $contact->email }}</p>
                            </div>
                        </div>
                        <button type="button" 
                                class="text-white/80 hover:text-white transition-colors duration-200" 
                                onclick="closeModal('replyModal{{ $contact->id }}')">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('admin.contacts.reply', $contact->id) }}" method="POST">
                    @csrf
                    <div class="p-6 bg-white">
                        <!-- Original Message Info -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-xl border-l-4 border-blue-500">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Original Message</p>
                            <p class="text-sm text-gray-700 mb-2"><span class="font-semibold">Subject:</span> {{ $contact->subject }}</p>
                            <p class="text-sm text-gray-600">{{ $contact->message }}</p>
                        </div>

                        <!-- Reply Textarea -->
                        <div class="mb-4">
                            <label for="reply_message{{ $contact->id }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                Your Reply
                            </label>
                            <textarea class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none" 
                                      id="reply_message{{ $contact->id }}" 
                                      name="reply_message" 
                                      rows="6" 
                                      placeholder="Type your reply here..."
                                      required></textarea>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200">
                        <button type="button" 
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200" 
                                onclick="closeModal('replyModal{{ $contact->id }}')">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Send Reply
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function closeModalOnBackdrop(event, modalId) {
    if (event.target.id === modalId) {
        closeModal(modalId);
    }
}

// Close modal on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.querySelectorAll('[id^="replyModal"]').forEach(modal => {
            modal.classList.add('hidden');
        });
        document.body.style.overflow = 'auto';
    }
});
</script>
@endsection