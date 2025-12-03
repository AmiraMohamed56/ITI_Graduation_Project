<?php

namespace App\Http\Controllers\Api\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patients\UpdateFullPatientProfileRequest;
use App\Http\Resources\Patient\PatientProfileResource;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PatientApiController extends Controller
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

    public function show($id)
    {
        $patient = Patient::with(['user', 'appointments.doctor.user', 'medicalRecords'])
            ->findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => new PatientProfileResource($patient),
        ]);
    }

    /**
     * Update patient (user + patient info)
     */
    public function update(UpdateFullPatientProfileRequest $request, $id)
    {
        $patient = Patient::with('user')->findOrFail($id);
        $user = $patient->user;

        // Unique email validation manually
        if ($request->filled('email')) {
            $exists = \App\Models\User::where('email', $request->email)
                ->where('id', '!=', $user->id)
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'email' => ['The email has already been taken.'],
                ]);
            }
        }

        DB::beginTransaction();
        try {
            // USER UPDATE
            $userData = $request->only(['name', 'email', 'phone']);

            if ($request->hasFile('profile_pic')) {
                if ($user->profile_pic) {
                    Storage::disk('public')->delete($user->profile_pic);
                }

                $path = $request->file('profile_pic')
                    ->store('profile_pics', 'public');

                $userData['profile_pic'] = $path;
            }

            $user->update($userData);

            // PATIENT UPDATE
            $patient->update(
                $request->only(['blood_type', 'chronic_diseases'])
            );

            DB::commit();

            // reload updated relations
            $patient->load(['user', 'appointments.doctor.user', 'medicalRecords']);

            return response()->json([
                'status' => true,
                'message' => 'Patient profile updated successfully',
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
