<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'newest');
        $request = Appointment::with(['doctor.user', 'doctor.specialty', 'patient.user'])
            ->where('status', 'completed')
            ->select('appointments.*');

        switch ($sort) {
            case 'oldest':
                $request->orderBy('appointments.created_at', 'asc');
                break;
                case 'newest':
            default:
                $request->orderBy('appointments.created_at', 'desc');
        }

        $visits = $request->paginate(10)->withQueryString();

        return view('admin.visits.index', compact('visits'));
    }
}
