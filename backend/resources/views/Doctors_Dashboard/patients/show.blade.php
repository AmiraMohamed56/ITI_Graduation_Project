@extends('Doctors_Dashboard.layouts.app')

@section('title', 'patient medical record')

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
                        <button
                            onclick="window.location.href='#'"
                            class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-edit"></i>
                            Update Record
                        </button>
                        <button
                            onclick="deleteRecord()"
                            class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-trash"></i>
                            Delete Record
                        </button>
                        <a href="#" class="text-gray-600 hover:text-gray-900">
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
                            <img
                                src="https://ui-avatars.com/api/?name=James+Carter&background=4F46E5&color=fff&size=100"
                                alt="Patient"
                                class="w-20 h-20 rounded-full border-4 border-blue-100"
                            />
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">James Carter</h2>
                                <p class="text-gray-600 text-sm mt-1">Patient ID: #PT0025</p>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        <i class="fas fa-circle text-xs mr-1"></i>
                                        Active Patient
                                    </span>
                                    <span class="text-sm text-gray-600">
                                        <i class="fas fa-calendar mr-1"></i>
                                        Last Visit: 17 Jun 2025
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Patient Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Date of Birth</p>
                            <p class="text-gray-900 font-medium">March 15, 1985</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Gender</p>
                            <p class="text-gray-900 font-medium">Male</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Blood Type</p>
                            <p class="text-gray-900 font-medium">O+</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Contact Number</p>
                            <p class="text-gray-900 font-medium">+1 (555) 123-4567</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Age</p>
                            <p class="text-gray-900 font-medium">39 years</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Email</p>
                            <p class="text-gray-900 font-medium">james.carter@email.com</p>
                        </div>
                    </div>
                </div>

                <!-- Medical Details Section -->
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Medical Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Visit Date & Time</p>
                            <p class="text-gray-900 font-medium">June 17, 2025 at 09:00 AM</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Department</p>
                            <p class="text-gray-900 font-medium">Anaesthesiology</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-600 mb-2">Chief Complaint</p>
                            <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">
                                Patient presents with persistent lower back pain for the past 3 weeks. Pain intensifies during physical activity and is accompanied by occasional muscle spasms. No history of recent trauma or injury reported.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Vital Signs Section -->
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Vital Signs</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-thermometer-half text-blue-600"></i>
                                <p class="text-sm text-gray-600">Temperature</p>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">36.8°C</p>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-heartbeat text-red-600"></i>
                                <p class="text-sm text-gray-600">Blood Pressure</p>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">120/80</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-heart text-green-600"></i>
                                <p class="text-sm text-gray-600">Heart Rate</p>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">72 bpm</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-weight text-purple-600"></i>
                                <p class="text-sm text-gray-600">Weight</p>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">75.5 kg</p>
                        </div>
                    </div>
                </div>

                <!-- Diagnosis & Treatment Section -->
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Diagnosis & Treatment</h3>
                    <div class="space-y-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-2 font-medium">Diagnosis</p>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-900">
                                    <strong>Primary Diagnosis:</strong> Acute Lumbar Strain (ICD-10: M54.5)
                                </p>
                                <p class="text-gray-900 mt-2">
                                    Physical examination reveals tenderness in the lumbar region with limited range of motion. No neurological deficits observed. Patient shows signs of muscular tension in the lower back area. X-ray imaging rules out any fractures or structural abnormalities.
                                </p>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-2 font-medium">Treatment Plan</p>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <ul class="space-y-2 text-gray-900">
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-600 mt-1 mr-2"></i>
                                        <span>Rest and avoid strenuous physical activities for 2 weeks</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-600 mt-1 mr-2"></i>
                                        <span>Apply cold compress for first 48 hours, then switch to heat therapy</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-600 mt-1 mr-2"></i>
                                        <span>Physical therapy sessions - 3 times per week for 4 weeks</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-600 mt-1 mr-2"></i>
                                        <span>Gentle stretching exercises as demonstrated</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-2 font-medium">Prescriptions</p>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="space-y-3">
                                    <div class="flex items-start justify-between border-b border-gray-200 pb-3">
                                        <div>
                                            <p class="font-medium text-gray-900">Ibuprofen 400mg</p>
                                            <p class="text-sm text-gray-600">Take 1 tablet every 6-8 hours with food</p>
                                        </div>
                                        <span class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded-full">14 days</span>
                                    </div>
                                    <div class="flex items-start justify-between border-b border-gray-200 pb-3">
                                        <div>
                                            <p class="font-medium text-gray-900">Muscle Relaxant (Cyclobenzaprine 10mg)</p>
                                            <p class="text-sm text-gray-600">Take 1 tablet at bedtime</p>
                                        </div>
                                        <span class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded-full">7 days</span>
                                    </div>
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <p class="font-medium text-gray-900">Topical Pain Relief Gel</p>
                                            <p class="text-sm text-gray-600">Apply to affected area 3-4 times daily</p>
                                        </div>
                                        <span class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded-full">As needed</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-2 font-medium">Additional Notes</p>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-900">
                                    Patient advised to maintain proper posture and ergonomics at workplace. Recommended to avoid heavy lifting and prolonged sitting. If symptoms persist or worsen after 2 weeks, consider MRI imaging to rule out disc herniation. Patient educated on proper body mechanics and core strengthening exercises.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Follow-up Section -->
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Follow-up Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Follow-up Required</p>
                            <p class="text-gray-900 font-medium">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-700">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Yes
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Next Visit Date</p>
                            <p class="text-gray-900 font-medium">July 1, 2025 at 10:00 AM</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-600 mb-2">Follow-up Instructions</p>
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <p class="text-gray-900">
                                    Patient to return in 2 weeks for progress evaluation. Bring physical therapy progress notes. If experiencing severe pain, numbness, or weakness in legs before scheduled appointment, contact office immediately or visit emergency department.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Doctor Information Section -->
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Attending Physician</h3>
                    <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-lg">
                        <img
                            src="https://ui-avatars.com/api/?name=Andrew+Clark&background=059669&color=fff&size=80"
                            alt="Doctor"
                            class="w-16 h-16 rounded-full"
                        />
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 text-lg">Dr. Andrew Clark</p>
                            <p class="text-gray-600 text-sm">Specialist in Anaesthesiology</p>
                            <p class="text-gray-600 text-sm mt-1">
                                <i class="fas fa-envelope mr-2"></i>
                                dr.clark@dreamsemr.com
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600 mb-1">Record Date</p>
                            <p class="text-gray-900 font-medium">June 17, 2025</p>
                            <div class="mt-2">
                                <img src="https://via.placeholder.com/150x50/4F46E5/ffffff?text=Digital+Signature" alt="Signature" class="h-8">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons at Bottom -->
            <div class="mt-6 flex items-center justify-between bg-white p-4 rounded-lg shadow">
                <a href="#" class="text-gray-600 hover:text-gray-900 flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Back to Records List
                </a>
                <div class="flex items-center gap-3">
                    <button
                        onclick="window.print()"
                        class="flex items-center gap-2 border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-print"></i>
                        Print Record
                    </button>
                    <button
                        class="flex items-center gap-2 border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-download"></i>
                        Download PDF
                    </button>
                </div>
            </div>
        </div>


    <script>
        function deleteRecord() {
            if (confirm('Are you sure you want to delete this medical record? This action cannot be undone.')) {
                alert('Medical record deleted successfully!');
                // Add your delete logic here
                // window.location.href = 'records-list.html';
            }
        }
    </script>
          @endsection

