<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Review;

class DoctorDashboardController extends Controller
{
    public function index()
    {
        $doctor = Auth::user()->doctor;
        
        // Statistics
        $todayAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('schedule_date', Carbon::today())
            ->count();
            
        $totalPatients = Appointment::where('doctor_id', $doctor->id)
            ->distinct('patient_id')
            ->count('patient_id');
            
        $pendingAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->count();
            
        $completedAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->count();
        
        // Patient Statistics for Chart (Last 7 Days)
        $patientStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            
            // New patients (first appointment with this doctor)
            $newPatients = Appointment::where('doctor_id', $doctor->id)
                ->whereDate('schedule_date', $date)
                ->whereNotExists(function($query) use ($doctor, $date) {
                    $query->select(\DB::raw(1))
                        ->from('appointments as a2')
                        ->whereColumn('a2.patient_id', 'appointments.patient_id')
                        ->where('a2.doctor_id', $doctor->id)
                        ->whereDate('a2.schedule_date', '<', $date);
                })
                ->distinct('patient_id')
                ->count('patient_id');
            
            // Old patients (returning patients)
            $oldPatients = Appointment::where('doctor_id', $doctor->id)
                ->whereDate('schedule_date', $date)
                ->whereExists(function($query) use ($doctor, $date) {
                    $query->select(\DB::raw(1))
                        ->from('appointments as a2')
                        ->whereColumn('a2.patient_id', 'appointments.patient_id')
                        ->where('a2.doctor_id', $doctor->id)
                        ->whereDate('a2.schedule_date', '<', $date);
                })
                ->distinct('patient_id')
                ->count('patient_id');
            
            $patientStats[] = [
                'date' => $date->format('d M'),
                'new' => $newPatients,
                'old' => $oldPatients,
                'total' => $newPatients + $oldPatients
            ];
        }
        
        $totalPatientCount = array_sum(array_column($patientStats, 'total'));
        
        // Recent Appointments
        $recentAppointments = Appointment::with(['patient.user', 'doctorSchedule'])
            ->where('doctor_id', $doctor->id)
            ->orderBy('schedule_date', 'desc')
            ->orderBy('schedule_time', 'desc')
            ->take(10)
            ->get();
        
        // Upcoming Appointments
        $upcomingAppointments = Appointment::with(['patient.user'])
            ->where('doctor_id', $doctor->id)
            ->where('status', 'confirmed')
            ->where('schedule_date', '>=', Carbon::today())
            ->orderBy('schedule_date', 'asc')
            ->orderBy('schedule_time', 'asc')
            ->take(5)
            ->get();
        
        // Recent Reviews
        $recentReviews = Review::with('patient.user')
            ->where('doctor_id', $doctor->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('Doctors_Dashboard.dashboard.index', compact(
            'doctor',
            'todayAppointments',
            'totalPatients',
            'pendingAppointments',
            'completedAppointments',
            'recentAppointments',
            'upcomingAppointments',
            'recentReviews',
            'patientStats',
            'totalPatientCount'
        ));
    }
}
