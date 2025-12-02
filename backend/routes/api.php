<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Patient\PatientApiController;

Route::get('api/patient', [PatientApiController::class, 'index']);

Route::get('api/patient/{id}', [PatientApiController::class, 'show']);

Route::post('api/patient/{id}/update-user', [PatientApiController::class, 'updateUser']);

Route::post('api/patient/{id}/update-info', [PatientApiController::class, 'updatePatient']);

Route::delete('api/patient/{id}/profile-pic', [PatientApiController::class, 'removeProfilePicture']);
