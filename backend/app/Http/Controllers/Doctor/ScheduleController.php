<?php

namespace App\Http\Controllers\Doctor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use App\Http\Requests\schedule\StoreScheduleRequest;
use App\Http\Requests\schedule\UpdateScheduleRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Notifications\DoctorScheduleCreated;
use App\Notifications\DoctorScheduleUpdated;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $doctorId = Auth::user()->doctor->id;
        $query = DoctorSchedule::with(['doctor.user'])
            ->where('doctor_id', $doctorId);
        // search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('day_of_week', 'like', "%{$search}%")
                    ->orWhere('start_time', 'like', "%{$search}%")
                    ->orWhere('end_time', 'like', "%{$search}%");
            });
        }

        // Filter by status
        // if ($request->has('status') && $request->status != '') {
        //     $query->where('is_active', $request->status == 'active' ? 1 : 0);
        // }

        // Sort functionality
        // $sortBy = $request->get('sort', 'day');
        // switch ($sortBy) {
        //     case 'time':
        //         $query->orderBy('start_time', 'asc');
        //         break;
        //     case 'duration':
        //         $query->orderBy('appointment_duration', 'desc');
        //         break;
        //     case 'newest':
        //         $query->orderBy('created_at', 'desc');
        //         break;
        //     default: // day
        //         $query->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')");
        //         break;
        // }


        //paginate and return view

        $schedules = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('Doctors_Dashboard.schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schedule = new DoctorSchedule();
        return view('Doctors_Dashboard.schedules.create', compact('schedule'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScheduleRequest $request)
    {
        try {
            $data = $request->validated();
            $data['doctor_id'] = Auth::user()->doctor->id;
            $data['is_active'] = $request->has('is_active') ? 1 : 0;
            $schedule = DoctorSchedule::create($data);
            // Load relationships for notification
            $schedule->load('doctor.user');

            // Notify all admins about new schedule
            $this->notifyAdmins($schedule, 'created');

            return redirect()->route('schedules.index')
                ->with('success', 'Schedule created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create schedule: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $schedule = DoctorSchedule::with(['doctor.user'])
            ->findOrFail($id);
        return view('Doctors_Dashboard.schedules.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $schedule = DoctorSchedule::with(['doctor.user'])->findOrFail($id);
        return view('Doctors_Dashboard.schedules.create', compact('schedule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScheduleRequest $request, string $id)
    {
        try {
            $schedule = DoctorSchedule::findOrFail($id);
            $data = $request->validated();
            $data['is_active'] = $request->has('is_active') ? 1 : 0;
            $schedule->update($data);

            // Load relationships for notification
            $schedule->load('doctor.user');

            // Notify all admins about schedule update
            $this->notifyAdmins($schedule, 'updated');

            return redirect()->route('schedules.index')
                ->with('success', 'Schedule updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update schedule: ' . $e->getMessage());
        }
    }




    /**
     * Permanently delete the schedule.
     */
    public function destroy(string $id)
    {
        try {
            $schedule = DoctorSchedule::findOrFail($id);
            $schedule->delete();

            return redirect()->route('schedules.index')
                ->with('success', 'Schedule deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete schedule: ' . $e->getMessage());
        }
    }

    /**
     * Notify all admins about schedule changes
     */
    private function notifyAdmins($schedule, $action)
    {
        try {
            // Get all admin users
            $admins = User::where('role', 'admin')->get();

            // Send appropriate notification to each admin
            foreach ($admins as $admin) {
                if ($action === 'created') {
                    $admin->notify(new DoctorScheduleCreated($schedule));
                } elseif ($action === 'updated') {
                    $admin->notify(new DoctorScheduleUpdated($schedule));
                }
            }

            Log::info("Schedule #{$schedule->id} {$action} - Notifications sent to admins");
        } catch (\Exception $e) {
            Log::error("Failed to send admin notifications for schedule #{$schedule->id}: " . $e->getMessage());
        }
    }
}
