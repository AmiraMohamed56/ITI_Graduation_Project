<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doctor\AppointmentController;
use App\Http\Controllers\Doctor\DoctorDashboardController;

use App\Http\Controllers\Admin\AdminSettingsController;


use App\Http\Controllers\Admin\PatientController;

Route::get('/', function () {
    return view('Doctors_Dashboard.schedule.show');
});
Route::middleware('auth')->group(function () {
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointments/{id}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::get('appointments/{id}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('appointments/{id}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::get('/docdashboard', [DoctorDashboardController::class, 'index'])->name('doctor.dashboard');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// =========================================== admin start =====================================================
// Route::get('/test-flash', function () {
//     return redirect('/admin/settings')->with('success', 'It works!');
// });
Route::get('admin/settings', [AdminSettingsController::class, 'edit'])->name('admin.settings.edit');
Route::patch('admin/settings', [AdminSettingsController::class, 'update'])->name('admin.settings.update');




Route::prefix('admin')->name('admin.')->group(function () {

    Route::resource('patients', PatientController::class);
});

Route::get('/admin/patients/{patient}', [PatientController::class, 'show'])->name('admin.patients.show');



// ===========================================  admin end  =====================================================
require __DIR__.'/auth.php';
