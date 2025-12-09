<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialty;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // ===== DashboardController.php =====
    public function index(Request $request)
    {
        // ===== Filter date range =====
        $from = $request->from ? Carbon::parse($request->from) : Carbon::today()->startOfMonth();
        $to = $request->to ? Carbon::parse($request->to) : Carbon::today();

        // ===== Basic Stats =====
        $stats = [
            'patients' => Patient::count(),
            'doctors' => Doctor::count(),
            'specialties' => Specialty::count(),
            'appointments' => Appointment::whereBetween('schedule_date', [$from, $to])->count(),
            'revenue' => Payment::where('status', 'paid')->whereBetween('created_at', [$from, $to])->sum('amount'),

            'pending' => Appointment::where('status', 'pending')->whereBetween('schedule_date', [$from, $to])->count(),
            'confirmed' => Appointment::where('status', 'confirmed')->whereBetween('schedule_date', [$from, $to])->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->whereBetween('schedule_date', [$from, $to])->count(),
            'completed' => Appointment::where('status', 'completed')->whereBetween('schedule_date', [$from, $to])->count(),
        ];

        // ===== Appointments Last 7 Days =====
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $last7Days->push([
                'date' => $date->format('M d'),
                'count' => Appointment::whereDate('schedule_date', $date)
                    ->whereBetween('schedule_date', [$from, $to])
                    ->count(),
            ]);
        }

        // ===== Revenue Per Day =====
        $revenuePerDay = collect();
        $period = new \DatePeriod($from, new \DateInterval('P1D'), $to->copy()->addDay());
        foreach ($period as $day) {
            $revenuePerDay->push([
                'date' => $day->format('M d'),
                'amount' => Payment::where('status', 'paid')
                    ->whereDate('created_at', $day)
                    ->sum('amount')
            ]);
        }
        

        // ===== Top Specialties (doctors count) =====
        $topSpecialties = Specialty::withCount(['doctors' => function ($q) use ($from, $to) {
            // count only doctors with appointments in this range
            $q->whereHas('appointments', function ($q2) use ($from, $to) {
                $q2->whereBetween('schedule_date', [$from, $to]);
            });
        }])->orderBy('doctors_count', 'DESC')->take(5)->get(['id', 'name']);

        // ===== Top Doctors =====
        $topDoctors = Doctor::with('user', 'specialty')
            ->whereHas('appointments', function ($q) use ($from, $to) {
                $q->whereBetween('schedule_date', [$from, $to]);
            })
            ->orderBy('rating', 'DESC')
            ->take(5)
            ->get();

        // ===== Latest Appointments =====
        $latestAppointments = Appointment::with(['patient.user', 'doctor.user'])
            ->whereBetween('schedule_date', [$from, $to])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'last7Days',
            'topSpecialties',
            'topDoctors',
            'latestAppointments',
            'from',
            'to',
            'revenuePerDay'
        ));
    }
}
