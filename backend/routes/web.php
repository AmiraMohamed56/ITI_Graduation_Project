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
use App\Http\Controllers\Api\Reviews\ReviewController as ReviewsReviewController;
use App\Http\Controllers\Doctor\NotificationController as DoctorNotificationController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\PayPalController;
// use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\AdminContactController;
use App\Http\Controllers\Admin\ReviewController;

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




// ADMINS ROUTES
// ==============================
// Admin Middleware
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {

    // admin dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // admin setting page
    Route::get('settings', [AdminSettingsController::class, 'edit'])->name('settings.edit');
    Route::patch('settings', [AdminSettingsController::class, 'update'])->name('settings.update');

    // admin appointment
    Route::resource('appointments', AdminAppointmentController::class)->names('appointments');

    // admin reviews
    Route::resource('reviews', ReviewController::class)->only([
        'index', 'show', 'destroy'
    ])->names('reviews');

    // admin payment
    Route::resource('payments', AdminPaymentController::class)->names('payments');

    // admin specialties
    Route::resource('specialties', AdminSpecialtyController::class)->names('specialties');
    // admin contacts
    Route::get('/contacts', [AdminContactController::class, 'index'])->name('contacts.index');
    Route::post('/contacts/{id}/reply', [AdminContactController::class, 'reply'])->name('contacts.reply');

    // admin doctors control
    // Settings
    Route::get('settings', [AdminSettingsController::class, 'edit'])->name('settings.edit');
    Route::patch('settings', [AdminSettingsController::class, 'update'])->name('settings.update');

    // Appointments
    Route::get('appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointments/create', [AdminAppointmentController::class, 'create'])->name('appointments.create');
    Route::get('appointments/{appointment}', [AdminAppointmentController::class, 'show'])->name('appointments.show');
    Route::get('appointments/edit/{appointment}', [AdminAppointmentController::class, 'edit'])->name('appointments.edit');
    Route::patch('appointments/{appointment}', [AdminAppointmentController::class, 'update'])->name('appointments.update');
    Route::post('appointments', [AdminAppointmentController::class, 'store'])->name('appointments.store');
    Route::delete('appointments/{appointment}', [AdminAppointmentController::class, 'destroy'])->name('appointments.destroy');

    // Payments
    Route::resource('payments', AdminPaymentController::class)->names('payments');

    // Specialties
    Route::resource('specialties', AdminSpecialtyController::class)->names('specialties');

    // Invoices
    Route::resource('invoices', InvoiceController::class)->names('invoice');

    // Doctors
    Route::get('doctors', [DoctorController::class, 'index'])->name('doctors.index');
    Route::get('doctors/create', [DoctorController::class, 'create'])->name('doctors.create');
    Route::get('doctors/trashed', [DoctorController::class, 'trashed'])->name('doctors.trashed');
    Route::post('doctors', [DoctorController::class, 'store'])->name('doctors.store');
    Route::get('doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');
    Route::get('doctors/{doctor}/edit', [DoctorController::class, 'edit'])->name('doctors.edit');
    Route::put('doctors/{doctor}', [DoctorController::class, 'update'])->name('doctors.update');
    Route::delete('doctors/{doctor}', [DoctorController::class, 'destroy'])->name('doctors.destroy');
    Route::post('doctors/{id}/restore', [DoctorController::class, 'restore'])->name('doctors.restore');

    // admin patients control
    Route::post('patients/{id}/restore', [PatientController::class, 'restore'])->name('patients.restore');
    // Patients
    Route::get('patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('patients/create', [PatientController::class, 'create'])->name('patients.create');
    Route::get('patients/trashed', [PatientController::class, 'trashed'])->name('patients.trashed');
    Route::post('patients', [PatientController::class, 'store'])->name('patients.store');
    Route::get('patients/{patient}', [PatientController::class, 'show'])->name('patients.show');
    Route::get('patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
    Route::post('patients/{id}/restore', [PatientController::class, 'restore'])->name('patients.restore');

    // patients visits
    Route::resource('visits', VisitController::class)->names('visits');

    // users logs
    Route::get('logs', [AdminLogController::class, 'index'])->name('logs.index');

    // payment invoice
    Route::resource('/invoices', InvoiceController::class)->names('invoice');
    // Admin Logs
    Route::get('logs', [AdminLogController::class, 'index'])->name('logs.index');

    // Visits
    Route::get('/visits', [VisitController::class, 'index'])->name('visits.index');
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
//payment
Route::middleware(['auth'])->group(function () {
    // PayPal routes
    Route::post('paypal/payment', [PayPalController::class, 'createPayment'])->name('paypal.payment');
    Route::get('paypal/success', [PayPalController::class, 'paymentSuccess'])->name('paypal.success');
    Route::get('paypal/cancel', [PayPalController::class, 'paymentCancel'])->name('paypal.cancel');
});




// DOCTORS ROUTES
// ==============================
// Doctor Middleware
Route::middleware(['auth', 'isDoctor'])->prefix('doctor')->group(function () {
    // doctor dashboard
    Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('doctor.dashboard');

    // Appointments
    Route::get('appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointments/{id}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::get('appointments/{id}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('appointments/{id}', [AppointmentController::class, 'update'])->name('appointments.update');

    // doctors medical records control with the medical files
    // Medical records
    Route::resource('medical_records', MedicalRecordController::class);
    Route::get('medical-files/{file}', [MedicalRecordController::class, 'downloadFile'])
        ->name('medical_files.download');

    //doctors schedules
    Route::resource('schedules', ScheduleController::class);

    // doctor profile setting
    // Schedule
    Route::resource('schedules', ScheduleController::class);

    // Profile
    Route::resource('profile_setting', ProfileSettingController::class);


    // doctor review
    Route::get('reviews', [ReviewsReviewController::class, 'index'])->name('reviews.index');
    Route::post('reviews/{id}/approve', [ReviewsReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('reviews/{id}/reject', [ReviewsReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('reviews/{id}', [ReviewsReviewController::class, 'destroy'])->name('reviews.destroy');
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
