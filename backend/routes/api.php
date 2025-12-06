<?php

use App\Http\Controllers\Api\medical_specialities\MedicalSpecialitiesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Patient\PatientApiController;
use App\Http\Controllers\Api\Doctor\DoctorController;
use App\Http\Controllers\Api\Patient\PatientAuthController;
use App\Http\Controllers\Api\Reviews\ReviewController;
// ============================== Booking Appointment start =======================================
use App\Http\Controllers\Api\Booking\BookingDoctorScheduleController;
use App\Http\Controllers\Api\Booking\BookingAppointmentController;
use App\Http\Controllers\Api\Booking\BookingDoctorController;
use App\Http\Controllers\Api\AI\SymptomsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Protected routes (authentication required)
Route::middleware(['auth:sanctum'])->group(function () {

});

// Specialty routes
Route::prefix('specialties')->group(function () {
    Route::get('/', [MedicalSpecialitiesController::class, 'index']); // Get all specialties
    Route::get('/{id}', [MedicalSpecialitiesController::class, 'show']); // Get specific specialty
    Route::get('/{id}/doctors', [MedicalSpecialitiesController::class, 'doctors']); // Get doctors by specialty
});

Route::options('{any}', function () {
    return response()->noContent();
})->where('any', '.*');


Route::get('/specialties', function () {
    return \App\Http\Resources\Booking\BookingSpecialtyResource::collection(\App\Models\Specialty::all());
});

Route::get('/doctors', [BookingDoctorController::class, 'index']);
Route::get('/doctor_schedules', [BookingDoctorScheduleController::class, 'index']); // supports ?doctor_id=&day_of_week=
Route::get('/appointments', [BookingAppointmentController::class, 'index']); // supports ?doctor_id=&schedule_date=
Route::post('/appointments', [BookingAppointmentController::class, 'store']);

// =============================== Booking Appointment end =======================================

// API Routes for Patient profile management
Route::get('patient', [PatientApiController::class, 'index']);
Route::get('patient/{id}', [PatientApiController::class, 'show']);
Route::post('patient/{id}/update-user', [PatientApiController::class, 'updateUser']);
Route::post('patient/{id}/update-info', [PatientApiController::class, 'updatePatient']);
Route::delete('patient/{id}/profile-pic', [PatientApiController::class, 'removeProfilePicture']);

//Api Routes for Doctor management
Route::apiResource('doctors', DoctorController::class);
Route::apiResource('reviews', ReviewController::class);


// Auth API Routes for Patient
Route::post('patient/register', [PatientAuthController::class, 'register']);
Route::post('patient/login', [PatientAuthController::class, 'login']);
Route::post('patient/send-verification-code', [PatientAuthController::class, 'sendVerificationCode']);
Route::post('patient/verify-code', [PatientAuthController::class, 'verifyCode']);
Route::post('patient/forgot-password', [PatientAuthController::class, 'forgotPassword']);
Route::post('patient/reset-password', [PatientAuthController::class, 'resetPassword']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('patient/logout', [PatientAuthController::class, 'logout']);
});


Route::post('/ai/symptoms', [SymptomsController::class, 'analyze']);
// ========================= google login start ========================================
use App\Http\Controllers\Auth\GoogleController;

Route::get('login/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('login/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// ========================= google login end ========================================
