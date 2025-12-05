<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminSpecialtyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Specialty::query();

        // Filter by search term
        if ($search = $request->query('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        // Paginate the results
        $specialties = $query->orderBy('id')->paginate(15)->withQueryString();

        return view('admin.specialties.index', compact('specialties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.specialties.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:specialties,name',
        ]);

        Specialty::create($validated);

        return redirect()->route('admin.specialties.index')->with('success', 'Specialty created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Specialty $specialty)
    {
        $id = $specialty->id;
        // $doctors = Doctor::where('specilty_id', $id)->with('doctor.user')->count();
        return view('admin.specialties.show', compact('specialty'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Specialty $specialty)
    {
        return view('admin.specialties.edit', compact('specialty'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Specialty $specialty)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('specialties')->ignore($specialty->id),
            ],
        ]);

        $specialty->update($validated);

        return redirect()->route('admin.specialties.index')
            ->with('success', 'Specialty updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialty $specialty)
    {
        // prevent deletion if doctors exist
        if ($specialty->doctors()->exists()) {
            return redirect()->route('admin.specialties.index')
                ->with('error', 'Cannot delete specialty with doctors assigned.');
        }

        $specialty->delete();

        return redirect()->route('admin.specialties.index')
            ->with('success', 'Specialty deleted successfully.');
    }
}
