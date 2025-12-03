<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Http\Requests\UpdateAppointmentRequest;

class AppointmentController extends Controller
{
    public function index()
    {
        $doctor = auth()->user()->doctor; // logged-in doctor

        $appointments = Appointment::with(['patient', 'doctor', 'doctorSchedule'])
            ->where('doctor_id', $doctor->id)
            ->orderBy('schedule_date', 'desc')
            ->orderBy('schedule_time')
            ->get();

        return view('Doctors_Dashboard.appointments.index', compact('appointments'));
    }

    public function show($id)
    {
        $appointment = Appointment::findOrFail($id);
        return view('Doctors_Dashboard.appointments.show', compact('appointment'));
    }

    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        return view('Doctors_Dashboard.appointments.edit', compact('appointment'));
    }

    public function update(UpdateAppointmentRequest $request, $id)
    {
        $appointments = Appointment::findOrFail($id);

        $validated = $request->validated();
        $appointments->fill($validated);

        // keep old price if not provided
        if (!isset($validated['price'])) {
            $appointments->price = $appointments->price;
        }

        $appointments->save();
        return redirect()
        ->route('appointments.index')
        ->with('success', 'Appointment updated successfully!');
    }



}
