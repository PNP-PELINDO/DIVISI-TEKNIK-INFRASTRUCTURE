<?php

namespace App\Http\Controllers;

use App\Models\Infrastructure;
use App\Models\BreakdownLog;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function process(Request $request)
    {
        $this->authorize('viewAny', Infrastructure::class);
        $user = auth()->user();
        $format = $request->input('format', 'pdf');
        if (!in_array($format, ['pdf', 'excel'])) {
            $format = 'pdf';
        }

        $infraQuery = Infrastructure::with('entity');
        $logQuery = BreakdownLog::with(['infrastructure' => fn($q) => $q->withTrashed()->with('entity')]);

        $applyDateFilters = function ($query) use ($request) {
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
        };

        $applyStatusFilters = function ($query) use ($request) {
            if ($request->filled('status')) {
                if ($request->status === 'available') {
                    $query->where('repair_status', 'resolved');
                } elseif ($request->status === 'breakdown') {
                    $query->where('repair_status', '!=', 'resolved');
                }
            }
        };

        // 1. Role Filtering
        if ($user->role !== 'superadmin') {
            $infraQuery->where('entity_id', $user->entity_id);
            $logQuery->whereHas('infrastructure', function ($q) use ($user) {
                $q->where('entity_id', $user->entity_id);
            });
        } elseif ($request->filled('entity_id')) {
            $infraQuery->where('entity_id', $request->entity_id);
            $logQuery->whereHas('infrastructure', function ($q) use ($request) {
                $q->where('entity_id', $request->entity_id);
            });
        }

        // 2. Category Filtering
        if ($request->filled('category')) {
            $infraQuery->where('category', $request->category);
            $logQuery->whereHas('infrastructure', function ($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        // 3. Status Filtering
        if ($request->filled('status')) {
            $infraQuery->where('status', $request->status);
            $applyStatusFilters($logQuery);
        } elseif (!$request->filled('start_date') && !$request->filled('end_date')) {
            // Default behavior if user has not specified status or date range:
            // show non-resolved breakdown logs only.
            $logQuery->where('repair_status', '!=', 'resolved');
        }

        // 4. Date Range Filtering (for Logs and Report consistency)
        $applyDateFilters($logQuery);

        if ($request->filled('start_date') || $request->filled('end_date')) {
            $infraQuery->whereHas('breakdownLogs', function ($q) use ($applyDateFilters, $applyStatusFilters) {
                $applyDateFilters($q);
                $applyStatusFilters($q);
            });
        }

        $allInfrastructures = $infraQuery->get();
        $allActiveBreakdowns = $logQuery->latest()->get();

        return view('admin.export.render', compact('allInfrastructures', 'allActiveBreakdowns', 'format'));
    }
}
