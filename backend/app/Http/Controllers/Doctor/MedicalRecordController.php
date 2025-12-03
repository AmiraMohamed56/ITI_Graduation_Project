<?php

namespace App\Http\Controllers\Doctor;
use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Http\Requests\medical_records\StoreMedicalRecordRequest;
use App\Http\Requests\medical_records\UpdateMedicalRecordRequest;
use App\Models\Appointment;
use App\Models\MedicalFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MedicalRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $doctor = Auth::user()->doctor;
    $query = MedicalRecord::with(['patient.user', 'doctor.user', 'appointment'])
    ->where('doctor_id',$doctor->id)
;

    // search
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('symptoms', 'like', "%{$search}%")
                ->orWhere('diagnosis', 'like', "%{$search}%")
                ->orWhere('medication', 'like', "%{$search}%")
                ->orWhereHas('patient.user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
        });
    }

    //paginate and return view
    $medicalRecords = $query->orderBy('created_at', 'desc')->paginate(10);
    return view('Doctors_Dashboard.medical_records.index', compact('medicalRecords'));
}


    /**
     * Show the form for creating a new resource.
     */
   public function create(Request $request)
{
    $medicalRecord = new MedicalRecord();
    $doctor = Auth::user()->doctor;
    $appointment = null;

    if ($request->filled('appointment_id')) {
        $appointment = Appointment::with(['patient.user'])
            ->where('doctor_id', $doctor->id)
            ->findOrFail($request->appointment_id);
    }

    // all appointments for this doctor
    $allAppointments = Appointment::where('doctor_id', $doctor->id)
        ->with(['patient.user'])
        ->get();

     // get patient ids that already have medical records with this doctor
    $patientsWithRecords = MedicalRecord::where('doctor_id', $doctor->id)
        ->pluck('patient_id')
        ->unique()
        ->toArray();

         // Filter appointments to only show patients without medical records
    $appointments = $allAppointments->filter(function($appointment) use ($patientsWithRecords) {
        return !in_array($appointment->patient_id, $patientsWithRecords);
    })->sortByDesc('schedule_date')->sortByDesc('schedule_time');

    return view('Doctors_Dashboard.medical_records.create', compact(
        'medicalRecord',
        'appointment',
        'appointments'
    ));
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedicalRecordRequest $request)
{
    try {
        // Create record
        $medicalRecord = MedicalRecord::create($request->validated());

        // Upload files if any
        if ($request->hasFile('medical_files')) {
            foreach ($request->file('medical_files') as $file) {
                $path = $file->store('medical_files','public');

                $medicalRecord->files()->create([
                    'file_path' => $path
                ]);
            }
        }

        return redirect()
            ->route('medical_records.index', $medicalRecord)
            ->with('success', 'Medical record created successfully');

    } catch (\Exception $e) {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Failed to create medical record: ' . $e->getMessage());
    }
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
          $medicalRecord = MedicalRecord::with(['patient.user', 'doctor.user', 'appointment'])
        ->findOrFail($id);
        return view('Doctors_Dashboard.medical_records.show', compact('medicalRecord'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedicalRecord $medicalRecord)
{
    // Load needed relationships
    $medicalRecord->load([
        'patient.user',
        'doctor.user',
        'appointment',
        'medicalFiles'
    ]);
    $doctor = Auth::user()->doctor;
    // for edit mode: no selecting appointments
    $appointments = collect();
    // current appointment
    $appointment = $medicalRecord->appointment;

    return view(
        'Doctors_Dashboard.medical_records.create',
        compact('medicalRecord', 'doctor', 'appointment', 'appointments')
    );
}


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedicalRecordRequest $request, string $id)
{
    try {
        // Find record
        $medicalRecord = MedicalRecord::findOrFail($id);

        // Update fields
        $medicalRecord->update($request->validated());

        // Handle uploaded new files
        if ($request->hasFile('medical_files')) {
            foreach ($request->file('medical_files') as $file) {
                $path = $file->store('medical_files', 'public');

                $medicalRecord->files()->create([
                    'file_path' => $path
                ]);
            }
        }

        // Handle removed files
        if ($request->filled('remove_files')) {
            foreach ($request->input('remove_files') as $fileId) {
                $file = $medicalRecord->files()->find($fileId);

                if ($file) {
                     Storage::disk('public')->delete($file->file_path);
                    $file->delete();
                }
            }
        }

        return redirect()
            ->route('medical_records.show', $medicalRecord)
            ->with('success', 'Medical record updated successfully');

    } catch (\Exception $e) {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Failed to update medical record: ' . $e->getMessage());
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // where('doctor_id', Auth::user()->doctor->id)
        $schedule = MedicalRecord::with(['patient.user', 'doctor.user', 'appointment'])
            ->findOrFail($id);

        $schedule->delete();

        return redirect()->route('medical_records.index')
            ->with('success', 'Schedule deleted successfully');
    }



/**
 * Download or view a medical file
 */
public function downloadFile($fileId)
{
    $file = MedicalFile::findOrFail($fileId);

    // Check if the authenticated doctor owns this file's medical record
    if ($file->medicalRecord->doctor_id !== Auth::user()->doctor->id) {
        abort(403, 'Unauthorized access to this file');
    }
    // Get file path
    $filePath = storage_path('app/public/' . $file->file_path);
    // Check if file exists
    if (!file_exists($filePath)) {
        abort(404, 'File not found');
    }
    // Return file response
    return response()->file($filePath);
}
}
