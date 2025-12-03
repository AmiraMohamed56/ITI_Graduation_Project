@extends('Doctors_Dashboard.layouts.app')

@section('title', 'Medical Record Details')

@section('content')
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Medical Record Details</h1>
                    <p class="text-gray-600 mt-1">Complete patient medical information</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('medical_records.edit', $medicalRecord->id) }}"
                        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-edit"></i>
                        Update Record
                    </a>
                    <form action="{{ route('medical_records.destroy', $medicalRecord->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this medical record? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-trash"></i>
                            Delete Record
                        </button>
                    </form>
                    <a href="{{ route('medical_records.index') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-times text-xl"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="bg-white rounded-lg shadow">
            <!-- Patient Information Section -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $medicalRecord->patient->user->name }}</h2>
                            <div class="flex items-center gap-3 mt-2">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                {{ $medicalRecord->patient->user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    <i class="fas fa-circle text-xs mr-1"></i>
                                    {{ ucfirst($medicalRecord->patient->user->status) }} Patient
                                </span>
                                <span class="text-sm text-gray-600">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Last Visit: {{ $medicalRecord->created_at->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-gray-900 mb-4">Patient Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @if ($medicalRecord->patient->blood_type)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Blood Type</p>
                            <p class="text-gray-900 font-medium">{{ $medicalRecord->patient->blood_type }}</p>
                        </div>
                    @endif

                    @if ($medicalRecord->patient->user->phone)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Contact Number</p>
                            <p class="text-gray-900 font-medium">{{ $medicalRecord->patient->user->phone }}</p>
                        </div>
                    @endif

                    <div>
                        <p class="text-sm text-gray-600 mb-1">Email</p>
                        <p class="text-gray-900 font-medium">{{ $medicalRecord->patient->user->email }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">Account Status</p>
                        <p class="text-gray-900 font-medium">
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            {{ $medicalRecord->patient->user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($medicalRecord->patient->user->status) }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">Patient Since</p>
                        <p class="text-gray-900 font-medium">{{ $medicalRecord->patient->created_at->format('F Y') }}</p>
                    </div>
                </div>

                @if ($medicalRecord->patient->chronic_diseases)
                    <div class="mt-6">
                        <p class="text-sm text-gray-600 mb-2 font-medium">Chronic Diseases</p>
                        <div class="bg-red-50 border border-red-200 p-4 rounded-lg">
                            <p class="text-gray-900">{{ $medicalRecord->patient->chronic_diseases }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Medical Details Section -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Medical Record Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Visit Date & Time</p>
                        <p class="text-gray-900 font-medium">{{ $medicalRecord->created_at->format('F d, Y \a\t h:i A') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">Last Updated</p>
                        <p class="text-gray-900 font-medium">{{ $medicalRecord->updated_at->format('F d, Y \a\t h:i A') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Symptoms Section -->
            @if ($medicalRecord->symptoms)
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Symptoms</h3>
                    <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                        <p class="text-gray-900 whitespace-pre-line">{{ $medicalRecord->symptoms }}</p>
                    </div>
                </div>
            @endif

            <!-- Diagnosis Section -->
            @if ($medicalRecord->diagnosis)
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Diagnosis</h3>
                    <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
                        <p class="text-gray-900 whitespace-pre-line">{{ $medicalRecord->diagnosis }}</p>
                    </div>
                </div>
            @endif

            <!-- Medication/Prescription Section -->
            @if ($medicalRecord->medication)
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Medication & Prescriptions</h3>
                    <div class="bg-green-50 border border-green-200 p-4 rounded-lg">
                        <p class="text-gray-900 whitespace-pre-line">{{ $medicalRecord->medication }}</p>
                    </div>
                </div>
            @endif

            <!-- Medical Files Section -->
            @if ($medicalRecord->medicalFiles && $medicalRecord->medicalFiles->count() > 0)
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-file-medical text-blue-600"></i>
                        Medical Files & Documents
                        <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full">
                            {{ $medicalRecord->medicalFiles->count() }}
                        </span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($medicalRecord->medicalFiles as $file)
                            @php
                                $extension = pathinfo($file->file_path, PATHINFO_EXTENSION);
                                $fileName = basename($file->file_path);
                                $fileUrl = route('medical_files.download', $file->id);

                                // Check file existence and get size safely
                                $fileExists = false;
                                $fileSize = 'Unknown';

                                try {
                                    $fullPath = storage_path('app/public/' . $file->file_path);
                                    if (file_exists($fullPath)) {
                                        $fileExists = true;
                                        $fileSizeBytes = filesize($fullPath);
                                        $fileSize = number_format($fileSizeBytes / 1024, 2) . ' KB';
                                    }
                                } catch (\Exception $e) {
                                    $fileExists = Storage::disk('public')->exists($file->file_path);
                                }

                                // Determine file icon and color based on extension
                                $iconClass = 'fa-file';
                                $bgColor = 'bg-gray-100';
                                $textColor = 'text-gray-600';

                                if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif'])) {
                                    $iconClass = 'fa-file-image';
                                    $bgColor = 'bg-blue-100';
                                    $textColor = 'text-blue-600';
                                } elseif (strtolower($extension) === 'pdf') {
                                    $iconClass = 'fa-file-pdf';
                                    $bgColor = 'bg-red-100';
                                    $textColor = 'text-red-600';
                                } elseif (in_array(strtolower($extension), ['doc', 'docx'])) {
                                    $iconClass = 'fa-file-word';
                                    $bgColor = 'bg-blue-100';
                                    $textColor = 'text-blue-600';
                                }
                            @endphp

                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="{{ $bgColor }} w-12 h-12 rounded-lg flex items-center justify-center">
                                            <i class="fas {{ $iconClass }} text-xl {{ $textColor }}"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900 truncate" title="{{ $fileName }}">
                                            {{ Str::limit($fileName, 30) }}
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ strtoupper($extension) }}
                                            @php
                                                try {
                                                    if (Storage::disk('public')->exists($file->file_path)) {
                                                        $fileSize = Storage::disk('public')->size($file->file_path);
                                                        echo ' • ' . number_format($fileSize / 1024, 2) . ' KB';
                                                    } else {
                                                        echo ' • File missing';
                                                    }
                                                } catch (\Exception $e) {
                                                    echo ' • Size unavailable';
                                                }
                                            @endphp
                                        <p class="text-xs text-gray-400 mt-1">
                                            Uploaded: {{ $file->created_at->format('M d, Y') }}
                                        </p>
                                        <div class="flex items-center gap-2 mt-3">
                                            @if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                                                <!-- Image Preview Button -->
                                                <button
                                                    onclick="openImageModal('{{ $fileUrl }}', '{{ $fileName }}')"
                                                    class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded transition-colors flex items-center gap-1">
                                                    <i class="fas fa-eye"></i>
                                                    Preview
                                                </button>
                                            @elseif (strtolower($extension) === 'pdf')
                                                <!-- PDF View Button -->
                                                <a href="{{ $fileUrl }}" target="_blank"
                                                    class="text-xs bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded transition-colors flex items-center gap-1">
                                                    <i class="fas fa-eye"></i>
                                                    View PDF
                                                </a>
                                            @else
                                                <!-- Other files - just download -->
                                                <a href="{{ $fileUrl }}"
                                                    class="text-xs bg-gray-600 hover:bg-gray-700 text-white px-3 py-1.5 rounded transition-colors flex items-center gap-1">
                                                    <i class="fas fa-eye"></i>
                                                    Open
                                                </a>
                                            @endif

                                            <a href="{{ $fileUrl }}" download
                                                class="text-xs border border-gray-300 text-gray-700 hover:bg-gray-50 px-3 py-1.5 rounded transition-colors flex items-center gap-1">
                                                <i class="fas fa-download"></i>
                                                Download
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Empty State if No Medical Data -->
            @if (
                !$medicalRecord->symptoms &&
                    !$medicalRecord->diagnosis &&
                    !$medicalRecord->medication &&
                    (!$medicalRecord->medicalFiles || $medicalRecord->medicalFiles->count() === 0))
                <div class="p-6 border-b border-gray-200">
                    <div class="text-center py-8">
                        <i class="fas fa-file-medical text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-lg">No medical details recorded yet</p>
                        <p class="text-gray-400 text-sm mt-1">Add symptoms, diagnosis, medication information, and files</p>
                    </div>
                </div>
            @endif

            <!-- Doctor Information Section -->
            {{-- <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Attending Physician</h3>
            <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-lg">
                <img
                    src="{{ $medicalRecord->doctor->user->profile_pic ?? 'https://ui-avatars.com/api/?name=' . urlencode($medicalRecord->doctor->user->name) . '&background=059669&color=fff&size=80' }}"
                    alt="{{ $medicalRecord->doctor->user->name }}"
                    class="w-16 h-16 rounded-full"
                />
                <div class="flex-1">
                    <p class="font-semibold text-gray-900 text-lg">Dr. {{ $medicalRecord->doctor->user->name }}</p>
                    <p class="text-gray-600 text-sm">Doctor ID: #DR{{ str_pad($medicalRecord->doctor_id, 4, '0', STR_PAD_LEFT) }}</p>
                    <p class="text-gray-600 text-sm mt-1">
                        <i class="fas fa-envelope mr-2"></i>
                        {{ $medicalRecord->doctor->user->email }}
                    </p>
                    @if ($medicalRecord->doctor->user->phone)
                    <p class="text-gray-600 text-sm mt-1">
                        <i class="fas fa-phone mr-2"></i>
                        {{ $medicalRecord->doctor->user->phone }}
                    </p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600 mb-1">Record Created</p>
                    <p class="text-gray-900 font-medium">{{ $medicalRecord->created_at->format('M d, Y') }}</p>
                    <p class="text-gray-600 text-xs mt-1">{{ $medicalRecord->created_at->format('h:i A') }}</p>
                </div>
            </div>
        </div> --}}
        </div>

        <!-- Action Buttons at Bottom -->
        <div class="mt-6 flex items-center justify-between bg-white p-4 rounded-lg shadow">
            <a href="{{ route('medical_records.index') }}"
                class="text-gray-600 hover:text-gray-900 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Back to Records List
            </a>
            <div class="flex items-center gap-3">
                <button onclick="window.print()"
                    class="flex items-center gap-2 border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-print"></i>
                    Print Record
                </button>
            </div>
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
        <div class="relative max-w-5xl w-full bg-white rounded-lg overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 id="modalFileName" class="text-lg font-semibold text-gray-900"></h3>
                <button onclick="closeImageModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <div class="p-4 max-h-[80vh] overflow-auto">
                <img id="modalImage" src="" alt="Medical file preview" class="w-full h-auto">
            </div>
        </div>
    </div>

    <script>
        function openImageModal(imageUrl, fileName) {
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('modalFileName').textContent = fileName;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });

        // Close modal on backdrop click
        document.getElementById('imageModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });
    </script>

    @if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif

    @if (session('error'))
        <script>
            alert('{{ session('error') }}');
        </script>
    @endif
@endsection
