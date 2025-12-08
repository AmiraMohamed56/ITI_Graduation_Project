<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use Illuminate\Http\Request;

class AdminLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AdminLog::query();

        if ($request->filled('user')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user . '%');
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.logs.index', [
            'logs' => $logs,
            'requestData' => $request->all(),
            'models' => ['User', 'Patient', 'Doctor', 'Appointment', 'Notification'],
            'actions' => ['login', 'logout', 'Created', 'Updated', 'Deleted', 'send_notification']
        ]);
    }
}
