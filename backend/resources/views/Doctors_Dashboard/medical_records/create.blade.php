@extends('Doctors_Dashboard.layouts.app')

@section('title', $medicalRecord->exists ? 'Edit Medical Record' : 'Create Medical Record')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $medicalRecord->exists ? 'Edit Medical Record' : 'Create Medical Record' }}
                </h1>
                <p class="text-gray-600 dark:text-gray-300 mt-1">
                    {{ $medicalRecord->exists ? 'Update patient medical information' : 'Record patient medical information and diagnosis' }}
                </p>
            </div>
            <a href="{{ route('medical_records.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
    <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form -->
    <form
        action="{{ $medicalRecord->exists ? route('medical_records.update', $medicalRecord) : route('medical_records.store') }}"
        method="POST" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-700">
        @csrf
        @if ($medicalRecord->exists)
        @method('PUT')
        @endif

        <div class="p-6">
            <!-- Patient Information Section -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    Patient Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if (!$medicalRecord->exists)
                    <!-- For Create: Select Appointment/Patient -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Select Patient (from Appointments) <span class="text-red-500">*</span>
                        </label>

                        @if ($appointments->isEmpty())
                        <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-700 text-yellow-800 dark:text-yellow-300 px-4 py-3 rounded-lg">
                            <p class="font-medium">No patients available</p>
                            <p class="text-sm mt-1">All patients who have appointments with you already have
                                medical records, or you don't have any appointments yet.</p>
                        </div>
                        @else
                        <select name="appointment_id" id="appointment_id"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            required>
                            <option value="">Select a patient</option>
                            @foreach ($appointments as $appt)
                            <option value="{{ $appt->id }}"
                                data-patient-name="{{ $appt->patient->user->name }}"
                                data-patient-email="{{ $appt->patient->user->email }}"
                                {{ old('appointment_id', $appointment?->id) == $appt->id ? 'selected' : '' }}>
                                {{ $appt->patient->user->name }}
                                ({{ $appt->patient->user->email }})
                                -
                                {{ $appt->schedule_date->format('M d, Y') }} at {{ $appt->schedule_time }}
                            </option>
                            @endforeach
                        </select>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                            <i class="fas fa-info-circle"></i>
                            Showing only patients who don't have medical records yet
                        </p>
                        @endif

                        @error('appointment_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preview selected patient info -->
                    <div id="patient-preview"
                        class="md:col-span-2 hidden bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                        <h3 class="font-medium text-blue-900 dark:text-blue-300 mb-2">Selected Patient:</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-blue-700 dark:text-blue-400">Name:</span>
                                <span id="preview-name" class="font-medium text-blue-900 dark:text-blue-300 ml-2"></span>
                            </div>
                            <div>
                                <span class="text-blue-700 dark:text-blue-400">Email:</span>
                                <span id="preview-email" class="font-medium text-blue-900 dark:text-blue-300 ml-2"></span>
                            </div>
                        </div>
                    </div>
                    @else
                    <!-- For Edit: Display Patient Info (Read-only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Patient Name</label>
                        <input type="text" value="{{ $medicalRecord->patient->user->name }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300" readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Patient Email</label>
                        <input type="text" value="{{ $medicalRecord->patient->user->email }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300" readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Appointment Date</label>
                        <input type="text"
                            value="{{ $medicalRecord->appointment->schedule_date->format('M d, Y') }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300" readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Appointment Time</label>
                        <input type="text" value="{{ $medicalRecord->appointment->schedule_time }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300" readonly>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Medical Details Section -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    Medical Details
                </h2>
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Symptoms
                        </label>
                        <textarea name="symptoms" rows="4" placeholder="Describe the patient's symptoms..."
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">{{ old('symptoms', $medicalRecord->symptoms) }}</textarea>
                        @error('symptoms')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Diagnosis
                        </label>
                        <textarea name="diagnosis" rows="4" placeholder="Enter detailed diagnosis..."
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">{{ old('diagnosis', $medicalRecord->diagnosis) }}</textarea>
                        @error('diagnosis')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Medication/Prescriptions
                        </label>
                        <textarea name="medication" rows="4" placeholder="List prescribed medications with dosage and frequency..."
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">{{ old('medication', $medicalRecord->medication) }}</textarea>
                        @error('medication')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Medical Files Section -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    Medical Files
                </h2>

                <!-- Existing Files (Edit Mode) -->
                @if ($medicalRecord->exists && $medicalRecord->medicalFiles->isNotEmpty())
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Existing Files
                    </label>
                    <div class="space-y-2">
                        @foreach ($medicalRecord->medicalFiles as $file)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-file-medical text-blue-500 dark:text-blue-400"></i>
                                <span class="text-sm dark:text-gray-300">{{ $file->name }}</span>
                                <a href="{{ $file->url }}" target="_blank"
                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm">
                                    <i class="fas fa-external-link-alt"></i> View
                                </a>
                            </div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="remove_files[]" value="{{ $file->id }}"
                                    class="form-checkbox h-4 w-4 text-red-600 dark:text-red-400">
                                <span class="text-sm text-red-600 dark:text-red-400">Remove</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Upload New Files -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ $medicalRecord->exists ? 'Upload Additional Files' : 'Upload Medical Files' }}
                    </label>
                    <input type="file" name="medical_files[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white file:bg-blue-50 file:border-0 file:py-2 file:px-4 file:rounded-lg file:text-blue-700 file:cursor-pointer dark:file:bg-gray-600 dark:file:text-gray-300">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Accepted formats: PDF, JPG, PNG, DOC, DOCX (Max 10MB per file)
                    </p>
                    @error('medical_files.*')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 rounded-b-lg border-t border-gray-200 dark:border-gray-600 flex items-center justify-end gap-3">
            <a href="{{ route('medical_records.index') }}"
                class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                Cancel
            </a>
            <button type="submit"
                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white rounded-lg transition-colors flex items-center gap-2">
                <i class="fas fa-save"></i>
                {{ $medicalRecord->exists ? 'Update Medical Record' : 'Save Medical Record' }}
            </button>
        </div>
    </form>
</div>

<script>
    // Show preview when appointment is selected
    document.getElementById('appointment_id')?.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const patientName = selectedOption.getAttribute('data-patient-name');
        const patientEmail = selectedOption.getAttribute('data-patient-email');

        // Show/hide preview
        const preview = document.getElementById('patient-preview');
        if (this.value) {
            document.getElementById('preview-name').textContent = patientName;
            document.getElementById('preview-email').textContent = patientEmail;
            preview.classList.remove('hidden');
        } else {
            preview.classList.add('hidden');
        }
    });
</script>
@endsection
