<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Patient\PatientApiController;
use App\Http\Controllers\Api\Doctor\DoctorController;
use App\Http\Controllers\Api\Patient\PatientAuthController;
use App\Http\Controllers\Api\Reviews\ReviewController;

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