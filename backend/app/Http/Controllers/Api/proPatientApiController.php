<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Patients\UpdatePatientRequest;
use App\Http\Requests\Patients\UpdateUserRequest;
use App\Http\Resources\Patient\PatientProfileResource;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class proPatientApiController extends Controller
{
    public function index()
    {
        $patients = Patient::with([
            'user',
            'appointments.doctor.user',
            'medicalRecords'
        ])->get();

        return response()->json([
            'status' => true,
            'message' => 'All patients fetched successfully',
            'data' => PatientProfileResource::collection($patients),
        ]);
    }


    /**
     * Show patient profile by patient id
     */
    public function show($id)
    {
        $patient = Patient::with(['user', 'appointments.doctor.user', 'medicalRecords'])->findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => new PatientProfileResource($patient),
        ]);
    }

    /**
     * Update user fields (name, email, phone, profile_pic)
     * - accepts multipart/form-data when uploading profile_pic
     */
    public function updateUser(UpdateUserRequest $request, $id)
    {
        $patient = Patient::with('user')->findOrFail($id);
        $user = $patient->user;

        // validate unique email manually (exclude current user's email)
        if ($request->filled('email')) {
            $exists = User::where('email', $request->email)->where('id', '!=', $user->id)->exists();
            if ($exists) {
                throw ValidationException::withMessages(['email' => ['The email has already been taken.']]);
            }
        }

        DB::beginTransaction();
        try {
            $data = $request->only(['name', 'email', 'phone']);

            // handle profile picture file if exists
            if ($request->hasFile('profile_pic')) {
                $file = $request->file('profile_pic');

                // delete old if exists
                if ($user->profile_pic) {
                    Storage::disk('public')->delete($user->profile_pic);
                }

                $path = $file->store('profile_pics', 'public');
                $data['profile_pic'] = $path;
            }

            $user->update($data);

            DB::commit();

            // reload relations to return up-to-date resource
            $patient->load('user');
            return response()->json([
                'status' => true,
                'message' => 'User info updated successfully',
                'data' => new PatientProfileResource($patient),
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Update failed',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Update patient-specific fields
     */
    public function updatePatient(UpdatePatientRequest $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $patient->update($request->only(['blood_type', 'chronic_diseases']));

        $patient->load(['user', 'appointments', 'medicalRecords']);

        return response()->json([
            'status' => true,
            'message' => 'Patient info updated successfully',
            'data' => new PatientProfileResource($patient),
        ]);
    }

    /**
     * Optional: Remove profile picture
     */
    public function removeProfilePicture($id)
    {
        $patient = Patient::with('user')->findOrFail($id);
        $user = $patient->user;

        if ($user->profile_pic) {
            Storage::disk('public')->delete($user->profile_pic);
            $user->update(['profile_pic' => null]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Profile picture removed',
            'data' => new PatientProfileResource($patient),
        ]);
    }
}
