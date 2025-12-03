<?php

use App\Http\Controllers\Api\medical_specialities\MedicalSpecialitiesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
