<?php

namespace App\Http\Controllers\Admin;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends AdminController
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        // Filtros
        if ($request->filled('user')) {
            $query->where('user_id', $request->user);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('model')) {
            $query->where('model_type', 'LIKE', "%{$request->model}%");
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $query->where('description', 'LIKE', "%{$request->search}%");
        }

        $logs = $query->latest()->paginate(30);

        // Datos para filtros
        $users = User::orderBy('name')->get(['id', 'name']);
        $actions = ActivityLog::distinct()->pluck('action');

        return view('admin.logs.index', compact('logs', 'users', 'actions'));
    }

    public function show(ActivityLog $log)
    {
        $log->load('user');
        return view('admin.logs.show', compact('log'));
    }

    public function userActivity(User $user)
    {
        $logs = ActivityLog::where('user_id', $user->id)
            ->latest()
            ->paginate(30);

        return view('admin.logs.user', compact('user', 'logs'));
    }

    public function export(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->latest()->get();

        $csv = "ID,Usuario,Acción,Descripción,Modelo,IP,Fecha\n";

        foreach ($logs as $log) {
            $csv .= implode(',', [
                $log->id,
                '"' . ($log->user?->name ?? 'Sistema') . '"',
                $log->action,
                '"' . str_replace('"', '""', $log->description) . '"',
                $log->model_type ?? '-',
                $log->ip_address ?? '-',
                $log->created_at->format('Y-m-d H:i:s'),
            ]) . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="activity_logs_' . date('Y-m-d') . '.csv"');
    }

    public function clear(Request $request)
    {
        $this->authorize('logs.clear');

        $days = $request->input('days', 90);

        $deleted = ActivityLog::where('created_at', '<', now()->subDays($days))->delete();

        ActivityLog::log('delete', "Limpió {$deleted} registros de actividad antiguos (más de {$days} días)");

        return back()->with('success', "{$deleted} registros eliminados correctamente.");
    }
}
