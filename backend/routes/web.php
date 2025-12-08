<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Doctor\ProfileSettingController;
use App\Http\Controllers\Doctor\AppointmentController;
use App\Http\Controllers\Doctor\DoctorDashboardController;
use App\Http\Controllers\Doctor\MedicalRecordController;
use App\Http\Controllers\Doctor\ScheduleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminLogController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminAppointmentController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminSpecialtyController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\VisitController;
use App\Http\Controllers\Invoice\InvoiceController;

use App\Http\Controllers\Admin\DashboardController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');





Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('settings', [AdminSettingsController::class, 'edit'])->name('settings.edit');
    Route::patch('settings', [AdminSettingsController::class, 'update'])->name('settings.update');

    Route::resource('appointments', AdminAppointmentController::class)->names('appointments');

    Route::resource('payments', AdminPaymentController::class)->names('payments');

    Route::resource('specialties', AdminSpecialtyController::class)->names('specialties');

    Route::get('doctors/trashed', [DoctorController::class, 'trashed'])->name('doctors.trashed');
    Route::resource('doctors', DoctorController::class)->names('doctors');
    Route::post('doctors/{id}/restore', [DoctorController::class, 'restore'])->name('doctors.restore');
    Route::resource('patients', PatientController::class)->names('patients');

    Route::post('patients/{id}/restore', [PatientController::class, 'restore'])->name('patients.restore');
    Route::get('patients/trashed', [PatientController::class, 'trashed'])->name('patients.trashed');
    Route::resource('patients', PatientController::class)->names('patients');
    Route::post('patients/{id}/restore', [PatientController::class, 'restore'])->name('patients.restore');

    Route::resource('visits', VisitController::class)->names('visits');

    Route::get('logs', [AdminLogController::class, 'index'])->name('logs.index');

    Route::resource('/invoices', InvoiceController::class)->names('invoice');
});

Route::middleware(['auth', 'isDoctor'])->prefix('doctor')->group(function () {
    Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('doctor.dashboard');

    Route::get('appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointments/{id}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::get('appointments/{id}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('appointments/{id}', [AppointmentController::class, 'update'])->name('appointments.update');

    Route::resource('medical_records', MedicalRecordController::class);
    Route::get('medical-files/{file}', [MedicalRecordController::class, 'downloadFile'])
        ->name('medical_files.download');

    Route::resource('schedules', ScheduleController::class);

    Route::resource('profile_setting', ProfileSettingController::class);
});


// ========================= google login start ========================================
use App\Http\Controllers\Auth\GoogleController;

Route::get('login/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('login/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// ========================= google login end ========================================



require __DIR__ . '/auth.php';
