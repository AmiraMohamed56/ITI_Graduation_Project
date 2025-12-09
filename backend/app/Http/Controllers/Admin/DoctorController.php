<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Doctors\StoreDoctorRequest;
use App\Http\Requests\Admin\Doctors\UpdateDoctorRequest;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'newest');
        $query = Doctor::with(['user', 'specialty'])
            ->select('doctors.*')
            ->join('users', 'doctors.user_id', '=', 'users.id');

        if($request->filled('name')){
            $query->where('users.name', 'LIKE', '%'.$request->name . '%');
        }

        if($request->filled('specialty')){
            $query->where('doctors.specialty_id', $request->specialty);
        }

        if($request->filled('email')){
            $query->where('users.email', 'LIKE', '%' . $request->email .'%');
        }
        
        switch ($sort) {
            case 'name':
                $query->orderBy('users.name', 'asc');
                break;
            case 'oldest':
                $query->orderBy('doctors.created_at', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('doctors.created_at', 'desc');
        }
        $doctors = $query->paginate(10)->withQueryString();
        $specialties = Specialty::all();

        return view('admin.doctors.index', compact('doctors', 'specialties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $specialties = Specialty::all();
        return view('admin.doctors.create', compact('specialties'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDoctorRequest $request)
    {
        DB::transaction(function ()  use ($request) {
            
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => 'doctor',
            'email_verified_at' => now(),
            'password' => Hash::make($request->password),
        ]);

        Doctor::create([
            'user_id' => $user->id,
            'specialty_id' => $request->specialty_id,
            'bio' => $request->bio,
            'education' => $request->education,
            'years_experience' => $request->years_experience,
            'gender' => $request->gender,
            'consultation_fee' => $request->consultation_fee,
            'available_for_online' => $request->has('available_for_online'),
        ]);

        });

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        $doctor->load(['user', 'specialty']);
        return view('admin.doctors.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        $specialties = Specialty::all();
        $doctor->load(['user', 'specialty']);
        return view('admin.doctors.edit', compact('doctor', 'specialties'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {
        $user = $doctor->user;
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone ?? $user->phone,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password),]);
        }

        $doctor->update($request->only([
            'specialty_id',
            'bio',
            'education',
            'years_experience',
            'gender',
            'consultation_fee',
            'available_for_online'
        ]));

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return redirect()->route('admin.doctors.index')->with('success', 'Doctor deleted successfully.');
    }

    public function trashed(Request $request)
    {
        $sort = $request->get('sort', 'newest');
        $query = Doctor::onlyTrashed()->with(['user', 'specialty'])
            ->select('doctors.*')
            ->join('users', 'doctors.user_id', '=', 'users.id');
        switch ($sort) {
            case 'name':
                $query->orderBy('users.name', 'asc');
                break;
            case 'oldest':
                $query->orderBy('doctors.created_at', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('doctors.created_at', 'desc');
        }
        $doctors = $query->paginate(5)->withQueryString();
        return view('admin.doctors.trashed', compact('doctors'));
    }

    public function restore($id)
    {
        $doctor = Doctor::onlyTrashed()->findOrFail($id);
        $doctor->restore();
        return redirect()->route('admin.doctors.trashed')->with('success', 'Doctor restored successfully.');
    }
}
