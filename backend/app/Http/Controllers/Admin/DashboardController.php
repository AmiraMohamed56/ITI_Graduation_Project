<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Specialty;
use App\Models\Review;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // الإحصائيات الأساسية
        $stats = [
            'total_doctors' => Doctor::count(),
            'total_patients' => Patient::count(),
            'total_appointments' => Appointment::count(),
            'total_specialties' => Specialty::count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount') ?? 0,
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
            'today_appointments' => Appointment::whereDate('schedule_date', today())->count(),
            'upcoming_appointments' => Appointment::where('status', 'confirmed')
                ->whereDate('schedule_date', '>=', today())
                ->count(),
        ];

        // الإحصائيات حسب الشهر
        $monthlyStats = $this->getMonthlyStats();

        // الإحصائيات حسب الحالة
        $statusStats = $this->getStatusStats();

        // الأطباء الأكثر حجزًا
        $topDoctors = $this->getTopDoctors();

        // التخصصات الأكثر طلبًا
        $topSpecialties = $this->getTopSpecialties();

        // آخر الحجوزات
        $recentAppointments = Appointment::with(['patient.user', 'doctor.user'])
            ->latest()
            ->take(10)
            ->get();

        // الإيرادات الشهرية
        $monthlyRevenue = $this->getMonthlyRevenue();

        // إحصائيات إضافية
        $weeklyStats = $this->getWeeklyStats();
        $doctorStats = $this->getDoctorStats();

        return view('admin.dashboard.index', compact(
            'stats',
            'monthlyStats',
            'statusStats',
            'topDoctors',
            'topSpecialties',
            'recentAppointments',
            'monthlyRevenue',
            'weeklyStats',
            'doctorStats'
        ));
    }

    private function getMonthlyStats()
    {
        $currentYear = date('Y');

        $monthlyAppointments = Appointment::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // ملء الأشهر الفارغة
        $allMonths = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $monthlyAppointments->where('month', $i)->first();
            $allMonths[$i] = $monthData ? $monthData->total : 0;
        }

        return $allMonths;
    }

    private function getStatusStats()
    {
        $statuses = Appointment::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        $result = [];
        $defaultStatuses = ['pending', 'confirmed', 'completed', 'cancelled'];

        foreach ($defaultStatuses as $status) {
            $statusData = $statuses->where('status', $status)->first();
            $result[$status] = $statusData ? $statusData->count : 0;
        }

        return $result;
    }

    private function getTopDoctors($limit = 5)
    {
        return Doctor::with(['user', 'specialty'])
            ->withCount('appointments')
            ->orderBy('appointments_count', 'desc')
            ->take($limit)
            ->get();
    }

    private function getTopSpecialties($limit = 5)
    {
        return Specialty::withCount('doctors')
            ->orderBy('doctors_count', 'desc')
            ->take($limit)
            ->get();
    }

    private function getMonthlyRevenue()
    {
        $currentYear = date('Y');

        $monthlyRevenue = Payment::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(amount) as revenue')
        )
            ->where('status', 'completed')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // ملء الأشهر الفارغة
        $allMonths = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $monthlyRevenue->where('month', $i)->first();
            $allMonths[$i] = $monthData ? (float) $monthData->revenue : 0;
        }

        return $allMonths;
    }

    private function getWeeklyStats()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $dailyStats = [];
        $currentDay = $startOfWeek->copy();

        while ($currentDay <= $endOfWeek) {
            $count = Appointment::whereDate('schedule_date', $currentDay)->count();
            $dailyStats[$currentDay->format('D')] = $count;
            $currentDay->addDay();
        }

        return $dailyStats;
    }

    private function getDoctorStats()
    {
        return [
            'active_doctors' => Doctor::whereHas('user', function ($query) {
                $query->where('status', 'active');
            })->count(),
            'male_doctors' => Doctor::where('gender', 'male')->count(),
            'female_doctors' => Doctor::where('gender', 'female')->count(),
            'avg_rating' => Doctor::avg('rating') ?? 0,
        ];
    }

    public function getChartData(Request $request)
    {
        $type = $request->get('type', 'monthly');

        switch ($type) {
            case 'monthly':
                $data = $this->getMonthlyStats();
                break;
            case 'revenue':
                $data = $this->getMonthlyRevenue();
                break;
            case 'status':
                $data = $this->getStatusStats();
                break;
            default:
                $data = [];
        }

        return response()->json($data);
    }
}
