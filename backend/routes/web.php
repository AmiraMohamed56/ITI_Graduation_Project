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
use App\Http\Controllers\Admin\notificationsController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\VisitController;
use App\Http\Controllers\Invoice\InvoiceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Doctor\NotificationController as DoctorNotificationController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ReviewController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');



Route::middleware('auth')->group(function () {
    // doctor appointment
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointments/{id}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::get('appointments/{id}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('appointments/{id}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::get('/docdashboard', [DoctorDashboardController::class, 'index'])->name('doctor.dashboard');
});





Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




// ADMINS ROUTES
// ==============================
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {

    // admin dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // admin setting page
    Route::get('settings', [AdminSettingsController::class, 'edit'])->name('settings.edit');
    Route::patch('settings', [AdminSettingsController::class, 'update'])->name('settings.update');

    // admin appointment
    Route::resource('appointments', AdminAppointmentController::class)->names('appointments');

    // admin payment
    Route::resource('payments', AdminPaymentController::class)->names('payments');

    // admin specialties
    Route::resource('specialties', AdminSpecialtyController::class)->names('specialties');

    // admin doctors control
    Route::get('doctors/trashed', [DoctorController::class, 'trashed'])->name('doctors.trashed');
    Route::resource('doctors', DoctorController::class)->names('doctors');
    Route::post('doctors/{id}/restore', [DoctorController::class, 'restore'])->name('doctors.restore');
    Route::resource('patients', PatientController::class)->names('patients');

    // admin patients control
    Route::post('patients/{id}/restore', [PatientController::class, 'restore'])->name('patients.restore');
    Route::get('patients/trashed', [PatientController::class, 'trashed'])->name('patients.trashed');
    Route::resource('patients', PatientController::class)->names('patients');
    Route::post('patients/{id}/restore', [PatientController::class, 'restore'])->name('patients.restore');

    // patients visits
    Route::resource('visits', VisitController::class)->names('visits');

    // users logs
    Route::get('logs', [AdminLogController::class, 'index'])->name('logs.index');

    // payment invoice
    Route::resource('/invoices', InvoiceController::class)->names('invoice');
});
// notifications routes
// Admin Notification Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/notifications', [notificationsController::class, 'index'])
        ->name('notifications.index');
    Route::get('/notifications/{id}', [notificationsController::class, 'show'])
        ->name('notifications.show');
    Route::post('/notifications/{id}/mark-as-read', [notificationsController::class, 'update'])
        ->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [notificationsController::class, 'markAllAsRead'])
        ->name('notifications.mark-all-as-read');
    Route::delete('/notifications/{id}', [notificationsController::class, 'destroy'])
        ->name('notifications.destroy');
    Route::delete('/notifications', action: [notificationsController::class, 'deleteAll'])
        ->name('notifications.delete-all');
});




// DOCTORS ROUTES
// ==============================
Route::middleware(['auth', 'isDoctor'])->prefix('doctor')->group(function () {
    // doctor dashboard
    Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('doctor.dashboard');

    // doctor appointment control
    Route::get('appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointments/{id}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::get('appointments/{id}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('appointments/{id}', [AppointmentController::class, 'update'])->name('appointments.update');

    // doctors medical records control with the medical files
    Route::resource('medical_records', MedicalRecordController::class);
    Route::get('medical-files/{file}', [MedicalRecordController::class, 'downloadFile'])
        ->name('medical_files.download');

    //doctors schedules
    Route::resource('schedules', ScheduleController::class);

    // doctor profile setting
    Route::resource('profile_setting', ProfileSettingController::class);


    // doctor review
    Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('reviews/{id}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('reviews/{id}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});


// notifications routes
// Doctor Notification Routes
Route::middleware(['auth'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/notifications', [DoctorNotificationController::class, 'index'])
        ->name('notifications.index');
    Route::get('/notifications/{id}', [DoctorNotificationController::class, 'show'])
        ->name('notifications.show');
    Route::post('/notifications/{id}/mark-as-read', [DoctorNotificationController::class, 'update'])
        ->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [DoctorNotificationController::class, 'markAllAsRead'])
        ->name('notifications.mark-all-as-read');
    Route::delete('/notifications/{id}', [DoctorNotificationController::class, 'destroy'])
        ->name('notifications.destroy');
    Route::delete('/notifications', [DoctorNotificationController::class, 'deleteAll'])
        ->name('notifications.delete-all');
});






// ========================= google login start ========================================

Route::get('login/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('login/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// ========================= google login end ========================================


require __DIR__ . '/auth.php';
