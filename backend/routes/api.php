<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Patient\PatientApiController;

// API Routes for Patient profile management
Route::get('patient', [PatientApiController::class, 'index']);
Route::get('patient/{id}', [PatientApiController::class, 'show']);
Route::post('patient/{id}/update-user', [PatientApiController::class, 'updateUser']);
Route::post('patient/{id}/update-info', [PatientApiController::class, 'updatePatient']);
Route::delete('patient/{id}/profile-pic', [PatientApiController::class, 'removeProfilePicture']);

