<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    // ================== INDEX ==================
    public function index()
    {
        $patients = Patient::with('user')->paginate(10);
        return view('admin.patients.index', compact('patients'));
    }

    // ================== CREATE ==================
    public function create()
    {
        return view('admin.patients.create');
    }

    // ================== STORE ==================
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'password' => 'required|min:6',
            'blood_type' => 'nullable',
            'chronic_diseases' => 'nullable'
        ]);

        // 1) أنشئ user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'patient',
            'status' => 'active'
        ]);

        // 2) أنشئ patient مرتبط باليوزر
        Patient::create([
            'user_id' => $user->id,
            'blood_type' => $request->blood_type,
            'chronic_diseases' => $request->chronic_diseases
        ]);

        return redirect()->route('admin.patients.index')->with('success', 'Patient created successfully.');
    }

    // ================== EDIT ==================
    public function edit($id)
    {
        $patient = Patient::with('user')->findOrFail($id);
        return view('admin.patients.edit', compact('patient'));
    }

    // ================== UPDATE ==================
    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        $user = $patient->user;

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required',
            'blood_type' => 'nullable',
            'chronic_diseases' => 'nullable'
        ]);

        // Update user
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone
        ]);

        // Update patient
        $patient->update([
            'blood_type' => $request->blood_type,
            'chronic_diseases' => $request->chronic_diseases
        ]);

        return redirect()->route('admin.patients.index')->with('success', 'Patient updated successfully.');
    }

    // ================== DELETE ==================
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return redirect()->back()->with('success', 'Patient deleted successfully.');
    }

    public function show(Patient $patient)
    {
        $appointments = $patient->appointments()->with('doctor')->get();

        return view('admin.patients.show', compact('patient', 'appointments'));
    }
}
