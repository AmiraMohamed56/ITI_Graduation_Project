<?php

use Illuminate\Support\Facades\Route;

Route::options('{any}', function () {
    return response()->noContent();
})->where('any', '.*');

use App\Http\Controllers\Api\Patient\PatientApiController;
use App\Http\Controllers\Api\Doctor\DoctorController;
use App\Http\Controllers\Api\Reviews\ReviewController;

// ============================== Booking Appointment start =======================================
use App\Http\Controllers\Api\Booking\BookingDoctorScheduleController;
use App\Http\Controllers\Api\Booking\BookingAppointmentController;
use App\Http\Controllers\Api\Booking\BookingDoctorController;

Route::get('/specialties', function() {
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


