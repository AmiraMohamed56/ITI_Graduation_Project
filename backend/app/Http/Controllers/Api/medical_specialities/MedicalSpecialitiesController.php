<?php

namespace App\Http\Controllers\Api\medical_specialities;
use App\Http\Controllers\Controller;
use App\Http\Resources\Doctor\DoctorResource;
use App\Http\Resources\medical_specialities\MedicalSpecialitiesResource;
use App\Models\Specialty;
use Illuminate\Http\Request;

class MedicalSpecialitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $specialties=Specialty::withCount('doctors')
        ->latest()
        ->paginate($request->input('per_page',8));

        return response()->json([
            'success' => true,
            'data' => MedicalSpecialitiesResource::collection($specialties),
            'meta' => [
                'current_page' => $specialties->currentPage(),
                'last_page' => $specialties->lastPage(),
                'per_page' => $specialties->perPage(),
                'total' => $specialties->total(),
                'from' => $specialties->firstItem(),
                'to' => $specialties->lastItem(),
            ],
            'message' => 'Specialties retrieved successfully.'
        ]);
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
        $specialty = Specialty::withCount('doctors')->find($id);
        if (!$specialty) {
            return response()->json([
                'success' => false,
                'message' => 'Specialty not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'=> new MedicalSpecialitiesResource($specialty),
            'message' => 'Specialty retrieved successfully.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Get all doctors for a specific specialty
     */
    public function doctors(Request $request, string $id)
    {
        $specialty = Specialty::find($id);

        if (!$specialty) {
            return response()->json([
                'success' => false,
                'message' => 'Specialty not found.'
            ], 404);
        }

        $doctors = $specialty->doctors()
            ->with(['user', 'specialty'])
            ->paginate($request->input('per_page', 10));

        return response()->json([
            'success' => true,
            'specialty' => [
                'id' => $specialty->id,
                'name' => $specialty->name,
            ],
            'data' => DoctorResource::collection($doctors),
            'meta' => [
                'current_page' => $doctors->currentPage(),
                'last_page' => $doctors->lastPage(),
                'per_page' => $doctors->perPage(),
                'total' => $doctors->total(),
                'from' => $doctors->firstItem(),
                'to' => $doctors->lastItem(),
            ],
            'message' => 'Doctors retrieved successfully.'
        ]);
    }
}
