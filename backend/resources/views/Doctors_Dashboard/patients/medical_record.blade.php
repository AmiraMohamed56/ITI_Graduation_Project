@extends('Doctors_Dashboard.layouts.app')

@section('title', 'medical record')

@section('content')
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Medical Record</h1>
                        <p class="text-gray-600 mt-1">Record patient medical information and diagnosis</p>
                    </div>
                    <a href="#" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-times text-xl"></i>
                    </a>
                </div>
            </div>

            <!-- Form -->
            <form class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <!-- Patient Information Section -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Patient Information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Patient ID <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    placeholder="e.g., PT0025"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Patient Name <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    placeholder="Enter patient full name"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Date of Birth <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="date"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Gender <span class="text-red-500">*</span>
                                </label>
                                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    <option value="">Select gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Blood Type
                                </label>
                                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select blood type</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Contact Number <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="tel"
                                    placeholder="Enter phone number"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Medical Details Section -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Medical Details
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Visit Date <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="datetime-local"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Department <span class="text-red-500">*</span>
                                </label>
                                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    <option value="">Select department</option>
                                    <option value="general">General Medicine</option>
                                    <option value="cardiology">Cardiology</option>
                                    <option value="dermatology">Dermatology</option>
                                    <option value="ent">ENT Surgery</option>
                                    <option value="orthopedics">Orthopaedics</option>
                                    <option value="pediatrics">Paediatrics</option>
                                    <option value="radiology">Radiology</option>
                                    <option value="dental">Dental Surgery</option>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Chief Complaint <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    rows="3"
                                    placeholder="Describe the main reason for visit..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Vital Signs Section -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Vital Signs
                        </h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Temperature (°C)
                                </label>
                                <input
                                    type="number"
                                    step="0.1"
                                    placeholder="36.5"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Blood Pressure
                                </label>
                                <input
                                    type="text"
                                    placeholder="120/80"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Heart Rate (bpm)
                                </label>
                                <input
                                    type="number"
                                    placeholder="72"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Weight (kg)
                                </label>
                                <input
                                    type="number"
                                    step="0.1"
                                    placeholder="70.5"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Diagnosis & Treatment Section -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Diagnosis & Treatment
                        </h2>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Diagnosis <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    rows="4"
                                    placeholder="Enter detailed diagnosis..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required
                                ></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Treatment Plan <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    rows="4"
                                    placeholder="Enter treatment plan and recommendations..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required
                                ></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Prescriptions
                                </label>
                                <textarea
                                    rows="4"
                                    placeholder="List prescribed medications with dosage and frequency..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                ></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Additional Notes
                                </label>
                                <textarea
                                    rows="3"
                                    placeholder="Any additional observations or notes..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Follow-up Section -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Follow-up
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Follow-up Required
                                </label>
                                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="no">No</option>
                                    <option value="yes">Yes</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Next Visit Date
                                </label>
                                <input
                                    type="date"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Doctor Information -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Doctor Information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Doctor Name <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    placeholder="Dr. John Smith"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Signature Date <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="date"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="bg-gray-50 px-6 py-4 rounded-b-lg border-t border-gray-200 flex items-center justify-end gap-3">
                    <button
                        type="button"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2"
                    >
                        <i class="fas fa-save"></i>
                        Save Medical Record
                    </button>
                </div>
            </form>
        </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Medical record saved successfully!');
            // Add your form submission logic here
        });
    </script>
      @endsection
