<?php

namespace App\Http\Controllers\Doctor;
use App\Http\Controllers\Controller;
use App\Http\Requests\profile_setting\UpdateProfileSettingRequest;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user()->load('doctor.specialty');
        $doctor = $user->doctor;
        $specialties = Specialty::all();

        return view('Doctors_Dashboard.setting.form', compact('user', 'doctor', 'specialties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {

        $user = Auth::user()->load('doctor.specialty');
        $doctor = $user->doctor;
        $specialties = Specialty::all();

        return view('Doctors_Dashboard.setting.form', compact('user', 'doctor', 'specialties'));
    }

    /**
     * Update the profile
     */
    public function update(UpdateProfileSettingRequest $request)
    {

            $user = Auth::user()->load('doctor');
            $doctor = $user->doctor;

          // Handle profile picture upload first
            $profilePicPath = $this->handleProfilePictureUpload($request, $user);

            // Update user model
            $user->fill([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            // Update password only if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            // Update profile picture path if uploaded
            if ($profilePicPath) {
                $user->profile_pic = $profilePicPath;
            }

            // Save user changes
            $user->save();

            // Update doctor model
            $user->doctor->fill([
                'specialty_id' => $request->specialty_id,
                'bio' => $request->bio,
                'education' => $request->education,
                'years_experience' => $request->years_experience,
                'gender' => $request->gender,
                'consultation_fee' => $request->consultation_fee,
                'available_for_online' => $request->boolean('available_for_online'),
            ]);

            // Save doctor changes
            $user->doctor->save();

            return redirect()->back()
                ->with('success', 'Profile updated successfully!');

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


      /**
     * Handle profile picture upload
     */
    private function handleProfilePictureUpload(UpdateProfileSettingRequest $request, $user): ?string
    {
        if (!$request->hasFile('profile_pic')) {
            return null;
        }

        // Delete old profile picture if exists
        if ($user->profile_pic) {
            Storage::disk('public')->delete($user->profile_pic);
        }

        // Store new profile picture
        $file = $request->file('profile_pic');
        $fileName = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();

        return $file->storeAs('profile_pictures', $fileName, 'public');
    }
}
