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
        $appointment = Appointment::findOrFail($id);

        $validated = $request->validated();
        $appointment->fill($validated);

        // keep old price if not provided
        if (!isset($validated['price'])) {
            $appointment->price = $appointment->price;
        }

        $appointment->save();

        return redirect()
            ->route('Doctors_Dashboard.appointments.index')
            ->with('success', 'Appointment updated successfully!');
    }



}
