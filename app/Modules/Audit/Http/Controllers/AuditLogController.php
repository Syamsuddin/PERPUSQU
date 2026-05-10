<?php

namespace App\Modules\Audit\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Audit\Models\ActivityLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::with('causer')
            ->when($request->log_name, fn ($q) => $q->where('log_name', $request->log_name))
            ->when($request->causer_id, fn ($q) => $q->where('causer_id', $request->causer_id))
            ->when($request->from_date, fn ($q) => $q->whereDate('created_at', '>=', $request->from_date))
            ->when($request->to_date, fn ($q) => $q->whereDate('created_at', '<=', $request->to_date))
            ->when($request->keyword, fn ($q) => $q->where('description', 'like', '%'.$request->keyword.'%'))
            ->latest()
            ->paginate(30)
            ->withQueryString();

        $logNames = ActivityLog::select('log_name')->distinct()->orderBy('log_name')->pluck('log_name');

        return view('modules.audit.index', compact('logs', 'logNames'));
    }
}
