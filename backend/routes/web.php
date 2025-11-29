<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doctor\AppointmentController;

use App\Http\Controllers\Admin\AdminSettingsController;

Route::get('/', function () {
    return view('Doctors_Dashboard.schedule.show');
});
Route::get('/appointments', [AppointmentController::class, 'index'])->middleware(['auth'])->name('appointments.index');
Route::get('appointments/{id}', [AppointmentController::class, 'show'])->middleware(['auth'])->name('appointments.show');
Route::get('appointments/{id}/edit', [AppointmentController::class, 'edit'])->middleware(['auth'])->name('appointments.edit');
Route::put('appointments/{id}', [AppointmentController::class, 'update'])->middleware(['auth'])->name('appointments.update');

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

// ===========================================  admin end  =====================================================
require __DIR__.'/auth.php';
