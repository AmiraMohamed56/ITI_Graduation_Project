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
use App\Http\Controllers\Admin\AdminAppointmentController;
use App\Http\Controllers\Admin\DoctorController;


use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\VisitController;

// Route::get('/', function () {
//     return view('Doctors_Dashboard.medical_records.index');
// });
use App\Http\Controllers\Api\Patient\PatientApiController ;
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

// settings
Route::get('admin/settings', [AdminSettingsController::class, 'edit'])->name('admin.settings.edit');
Route::patch('admin/settings', [AdminSettingsController::class, 'update'])->name('admin.settings.update');
// ===========================================  admin end  =====================================================


// appointments
Route::get('/admin/appointments', [AdminAppointmentController::class, 'index'])->name('admin.appointments.index');
Route::get('/admin/appointments/create', [AdminAppointmentController::class, 'create'])->name('admin.appointments.create');
Route::get('/admin/appointments/{appointment}', [AdminAppointmentController::class, 'show'])->name('admin.appointments.show');
Route::get('/admin/appointments/edit/{appointment}', [AdminAppointmentController::class, 'edit'])->name('admin.appointments.edit');
Route::patch('/admin/appointments/{appointment}', [AdminAppointmentController::class, 'update'])->name('admin.appointments.update');
Route::post('/admin/appointments', [AdminAppointmentController::class, 'store'])->name('admin.appointments.store');
Route::delete('/admin/appointments/{appointment}', [AdminAppointmentController::class, 'destroy'])->name('admin.appointments.destroy');


// patients
Route::get('/patients', [PatientController::class, 'index'])->name('admin.patients.index');
Route::get('/patients/create', [PatientController::class, 'create'])->name('admin.patients.create');
Route::get('/patients/trashed', [PatientController::class, 'trashed'])->name('admin.patients.trashed');
Route::post('/patients', [PatientController::class, 'store'])->name('admin.patients.store');
Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('admin.patients.show');
Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])->name('admin.patients.edit');
Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('admin.patients.update');
Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('admin.patients.destroy');

// Trashed / Soft Delete
Route::post('/patients/{id}/restore', [PatientController::class, 'restore'])->name('admin.patients.restore');



// ===========================================  admin end  =====================================================


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Doctor Routes in Admin Dashboard
Route::get('/doctors', [DoctorController::class, 'index'])->name('admin.doctors.index');
Route::get('/doctors/create', [DoctorController::class, 'create'])->name('admin.doctors.create');
Route::get('/doctors/trashed', [DoctorController::class, 'trashed'])->name('admin.doctors.trashed');
Route::post('/doctors', [DoctorController::class, 'store'])->name('admin.doctors.store');
Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('admin.doctors.show');
Route::get('/doctors/{doctor}/edit', [DoctorController::class, 'edit'])->name('admin.doctors.edit');
Route::put('/doctors/{doctor}', [DoctorController::class, 'update'])->name('admin.doctors.update');
Route::delete('/doctors/{doctor}', [DoctorController::class, 'destroy'])->name('admin.doctors.destroy');
Route::post('/doctors/{id}/restore', [DoctorController::class, 'restore'])->name('admin.doctors.restore');

// Visits Route in Admin Dashboard
Route::get('/visits', [VisitController::class, 'index'])->name('admin.visits.index');



// doctor dashboard routes
// --------------------------------------
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

// ---------------------------------------------------------------------------------------------------------------

require __DIR__.'/auth.php';
