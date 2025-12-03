<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Doctor\ProfileSettingController;
use App\Http\Controllers\Doctor\AppointmentController;
use App\Http\Controllers\Doctor\DoctorDashboardController;
use App\Http\Controllers\Doctor\MedicalRecordController;
use App\Http\Controllers\Doctor\ScheduleController;
use Illuminate\Support\Facades\Route;




use App\Http\Controllers\Admin\AdminSettingsController;

// Route::get('/', function () {
//     return view('Doctors_Dashboard.medical_records.index');
// });
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

    // doctore profile setting routes
    Route::get('/profile-settings', [ProfileSettingController::class, 'edit'])->name('profile.settings.edit');
    Route::put('/profile-settings', [ProfileSettingController::class, 'update'])->name('profile.settings.update');

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
// ===========================================  admin end  =====================================================


// doctor dashboard routes

// medical records routes
Route::resource('medical_records', MedicalRecordController::class);
Route::get('/medical-files/{file}', [MedicalRecordController::class, 'downloadFile'])
    ->name('medical_files.download');

// schedule routes
Route::resource('schedules', ScheduleController::class);

// profile routes
Route::resource('profile_setting', ProfileSettingController::class);

// notification routes
Route::resource('notifications', NotificationController::class);

require __DIR__.'/auth.php';
