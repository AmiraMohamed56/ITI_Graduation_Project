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
    public function index(Request $request)
    {
        $query = Patient::with('user');

        // ======== Filtering ========
        if ($request->name) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->name}%");
            });
        }

        if ($request->email) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('email', 'like', "%{$request->email}%");
            });
        }

        if ($request->phone) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('phone', 'like', "%{$request->phone}%");
            });
        }

        if ($request->blood_type) {
            $query->where('blood_type', $request->blood_type);
        }

        // ======== Sorting ========
        if ($request->sort == 'name') {
            $query->join('users', 'patients.user_id', '=', 'users.id')
                ->orderBy('users.name');
        } elseif ($request->sort == 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $patients = $query->paginate(10)->withQueryString(); // important to keep query params in pagination

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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'patient',
            'status' => 'active'
        ]);

        Patient::create([
            'user_id' => $user->id,
            'blood_type' => $request->blood_type,
            'chronic_diseases' => $request->chronic_diseases
        ]);

        return redirect()->route('admin.patients.index')->with('success', 'Patient created successfully.');
    }

    // ================== SHOW ==================
    public function show(Patient $patient)
    {
        $appointments = $patient->appointments()->with('doctor')->get();

        return view('admin.patients.show', compact('patient', 'appointments'));
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

    // ================== Soft Deleted / Trash ==================
    public function trashed()
    {
        $patients = Patient::onlyTrashed()->with('user')->paginate(10);
        return view('admin.patients.trashed', compact('patients'));
    }

    public function restore($id)
    {
        $patient = Patient::onlyTrashed()->findOrFail($id);
        $patient->restore();

        return redirect()->route('admin.patients.index')->with('success', 'Patient restored successfully.');
    }
}
