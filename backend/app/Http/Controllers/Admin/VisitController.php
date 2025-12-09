<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Specialty;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'newest');
        $query = Appointment::with(['doctor.user', 'doctor.specialty', 'patient.user'])
            ->where('status', 'completed')
            ->select('appointments.*');

        if ($request->filled('patient_name')) {
            $query->whereHas('patient.user', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->patient_name . '%');
            });
        }

        if ($request->filled('doctor_name')) {
            $query->whereHas('doctor.user', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->doctor_name . '%');
            });
        }

        switch ($sort) {
            case 'oldest':
                $query->orderBy('appointments.created_at', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('appointments.created_at', 'desc');
        }

        $visits = $query->paginate(10)->withQueryString();
        $specialties = Specialty::all();

        return view('admin.visits.index', compact('visits', 'specialties'));
    }
}
