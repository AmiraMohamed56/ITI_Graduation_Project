<?php

namespace App\Http\Controllers\Api\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Http\Resources\Doctor\DoctorResource;
use App\Http\Requests\Api\Doctor\DoctorFilterRequest;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(DoctorFilterRequest $request)
    {
        $query = Doctor::with(['user', 'specialty', 'schedules', 'reviews.patient.user:id,name']);

        //Search by doctor name
        if ($request->filled('name')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$request->name}%"));
        }

        //Search by specialty
        if ($request->filled('specialty')) {
            $query->whereHas('specialty', fn($q) => $q->where('name', 'like', "%{$request->specialty}%"));
        }

        //Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        //Filter by online availability
        if (!is_null($request->available_for_online)) {
            $query->where('available_for_online', $request->available_for_online);
        }

        $doctors = $query->get();

        return response()->json([
            'status' => true,
            'message' => 'Doctors retrieved successfully',
            'data' => DoctorResource::collection($doctors),
        ]);
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
        $doctor = Doctor::with(['user', 'specialty', 'schedules', 'reviews.patient.user:id,name'])->find($id);

    if (!$doctor) {
        return response()->json([
            'status' => false,
            'message' => 'Doctor not found',
        ], 404);
    }

    return response()->json([
        'status' => true,
        'message' => 'Doctor retrieved successfully',
        'data' => new DoctorResource($doctor)
    ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
}
