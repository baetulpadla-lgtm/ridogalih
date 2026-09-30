<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', AuditLog::class);

        $query = AuditLog::with('user')->latest();

        if ($request->filled('event')) {
            $query->where('event', strip_tags((string)$request->event));
        }

        if ($request->filled('table_name')) {
            $query->where('table_name', strip_tags((string)$request->table_name));
        }

        if ($request->filled('search')) {
            $search = strip_tags((string)$request->search); // [ANTI-XSS]
            $query->where(function($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                  ->orWhere('table_name', 'like', "%{$search}%")
                  ->orWhere('record_id', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $auditLogs = $query->paginate(15)->withQueryString();
        $tables = AuditLog::select('table_name')->distinct()->pluck('table_name');

        return view('admin.audit_logs.index', compact('auditLogs', 'tables'));
    }

    public function show(AuditLog $auditLog): View
    {
        Gate::authorize('view', $auditLog);

        $auditLog->loadMissing('user');
        return view('admin.audit_logs.show', compact('auditLog'));
    }
}
