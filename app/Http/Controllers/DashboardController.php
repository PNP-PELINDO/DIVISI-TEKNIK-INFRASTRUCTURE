<?php

namespace App\Http\Controllers;

use App\Models\Infrastructure;
use App\Models\BreakdownLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // -------------------------------------------------------------
        // FILTER PARAMETERS
        // -------------------------------------------------------------
        $filterEntity = request('entity_id');
        $filterCategory = request('category');

        // Jika user bukan superadmin, paksa filter entity ke entity_id miliknya
        if ($user->role !== 'superadmin') {
            $filterEntity = $user->entity_id;
            
            // Jika operator tidak punya entity_id, pastikan dia tidak melihat apa-apa (leakage guard)
            if (!$filterEntity) {
                $filterEntity = -1; // ID yang tidak mungkin ada
            }
        }

        // Ambil semua entitas untuk dropdown filter (Hanya untuk Superadmin)
        $allEntities = collect();
        if ($user->role === 'superadmin') {
            $allEntities = \App\Models\Entity::orderBy('name')->get();
        }

        // Menentukan Nama Area untuk UI
        $areaName = 'Pusat (Seluruh Regional)';
        if ($filterEntity && $filterEntity != -1) {
            $entityModel = \App\Models\Entity::find($filterEntity);
            $areaName = $entityModel ? $entityModel->name : 'Area Tidak Diketahui';
        }

        // Siapkan Query Dasar
        $infraQuery = Infrastructure::with('entity');
        $logQuery = BreakdownLog::with(['infrastructure' => fn($q) => $q->withTrashed()->with('entity')])->where('repair_status', '!=', 'resolved');

        // Apply Filters to Base Queries
        if ($filterEntity) {
            $infraQuery->where('entity_id', $filterEntity);
            $logQuery->whereHas('infrastructure', fn($q) => $q->where('entity_id', $filterEntity));
        }

        if ($filterCategory) {
            $infraQuery->where('category', $filterCategory);
            $logQuery->whereHas('infrastructure', fn($q) => $q->where('category', $filterCategory));
        }

        // MAJOR FIX: Optimize statistics with single query using withCount + raw query
        $allInfrastructures = $infraQuery->get();
        $stats = [
            'total' => $allInfrastructures->count(),
            'available' => $allInfrastructures->where('status', 'available')->count(),
            'breakdown' => $allInfrastructures->where('status', 'breakdown')->count(),
        ];

        // Ambil Data Infrastruktur untuk laporan (untuk export modal jika masih dibutuhkan)
        $infrastructures = $infraQuery->latest()->limit(10)->get();

        // -------------------------------------------------------------
        // NEW ANALYTICS DATA
        // -------------------------------------------------------------

        // 1. Top 5 Infrastruktur Sering Rusak (berdasarkan total riwayat log kerusakan)
        $frequentInfrastructures = Infrastructure::withTrashed()
            ->with(['entity', 'breakdownLogs' => function($q) {
                $q->latest()->take(5)->with('createdBy');
            }])
            ->withCount('breakdownLogs')
            ->when($filterEntity, fn($q) => $q->where('entity_id', $filterEntity))
            ->when($filterCategory, fn($q) => $q->where('category', $filterCategory))
            ->having('breakdown_logs_count', '>', 0)
            ->orderByDesc('breakdown_logs_count')
            ->take(5)
            ->get();

        // 2. Kategori Infrastruktur yang Paling Sering Rusak
        $breakdownsByCategory = \DB::table('breakdown_logs')
            ->join('infrastructures', 'breakdown_logs.infrastructure_id', '=', 'infrastructures.id')
            ->when($filterEntity, fn($q) => $q->where('infrastructures.entity_id', $filterEntity))
            ->when($filterCategory, fn($q) => $q->where('infrastructures.category', $filterCategory))
            ->select('infrastructures.category', \DB::raw('COUNT(breakdown_logs.id) as total_breakdowns'))
            ->groupBy('infrastructures.category')
            ->pluck('total_breakdowns', 'category')
            ->toArray();

        // 3. Top Terminal Banyak Kerusakan (Khusus Superadmin)
        $frequentEntities = collect();
        if ($user->role === 'superadmin') {
            $frequentEntities = \DB::table('entities')
                ->join('infrastructures', 'entities.id', '=', 'infrastructures.entity_id')
                ->join('breakdown_logs', 'infrastructures.id', '=', 'breakdown_logs.infrastructure_id')
                ->select('entities.name', \DB::raw('COUNT(breakdown_logs.id) as total_breakdowns'))
                ->groupBy('entities.id', 'entities.name')
                ->orderByDesc('total_breakdowns')
                ->take(5)
                ->get();
        }

        // 4. Laporan Urgent / Mendesak (Status 'reported' atau yang paling lama belum selesai)
        $urgentBreakdowns = (clone $logQuery)
            ->with(['infrastructure' => function($q) {
                $q->withTrashed()->with(['entity', 'breakdownLogs' => function($sq) {
                    $sq->latest()->take(5)->with('createdBy');
                }]);
            }, 'createdBy'])
            ->where('repair_status', 'reported')
            ->orderBy('created_at', 'asc') // Yang paling lama dilaporkan tapi masih reported
            ->take(5)
            ->get();

        // 5. Laporan Terbaru (Activity Feed)
        $recentLogs = (clone $logQuery)->latest()->take(5)->get();

        // Ambil SEMUA Log Kerusakan yang belum resolved untuk Laporan PDF/Excel
        $allActiveBreakdowns = (clone $logQuery)->latest()->get();

        // 6. Actionable Metrics (Status Tiket Aktif & Kesiapan Alat)
        $activeTicketStats = (clone $logQuery)
            ->select('repair_status', \DB::raw('count(*) as total'))
            ->groupBy('repair_status')
            ->pluck('total', 'repair_status')
            ->toArray();

        $stats['reported'] = $activeTicketStats['reported'] ?? 0;
        $stats['on_progress'] = $activeTicketStats['on_progress'] ?? 0;
        $stats['order_part'] = $activeTicketStats['order_part'] ?? 0;
        $stats['readiness_rate'] = $stats['total'] > 0 ? round(($stats['available'] / $stats['total']) * 100, 1) : 0;

        // -------------------------------------------------------------
        // ANALYTICS DATA MERGE FOR CHARTS
        // -------------------------------------------------------------
        
        // Logika Tren Laporan (30 Hari Terakhir)
        $trendData = BreakdownLog::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->when($filterEntity || $filterCategory, function($q) use ($filterEntity, $filterCategory) {
                $q->whereHas('infrastructure', function($sq) use ($filterEntity, $filterCategory) {
                    if ($filterEntity) $sq->where('entity_id', $filterEntity);
                    if ($filterCategory) $sq->where('category', $filterCategory);
                });
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $trendLabels = $trendData->pluck('date')->map(fn($d) => date('d M', strtotime($d)));
        $trendCounts = $trendData->pluck('count');

        // Logika Chart Distribusi Rasio Kesiapan
        if ($user->role === 'superadmin') {
            $entities = \App\Models\Entity::with('infrastructures')->get();
            $labels = [];
            $ready = [];
            $breakdown = [];

            foreach ($entities as $e) {
                $labels[] = $e->name;
                $ready[] = $e->infrastructures->where('status', 'available')->count();
                $breakdown[] = $e->infrastructures->where('status', 'breakdown')->count();
            }
        } else {
            $labels = ['Peralatan', 'Fasilitas', 'Utilitas'];
            $cats = ['equipment', 'facility', 'utility'];
            $ready = [];
            $breakdown = [];

            foreach ($cats as $c) {
                $ready[] = $allInfrastructures->where('category', $c)->where('status', 'available')->count();
                $breakdown[] = $allInfrastructures->where('category', $c)->where('status', 'breakdown')->count();
            }
        }

        $chartData = [
            'labels' => $labels,
            'ready' => $ready,
            'breakdown' => $breakdown,
            'trendLabels' => $trendLabels,
            'trendCounts' => $trendCounts,
            'entity_name' => $areaName,
            'breakdownsByCategory' => [
                'equipment' => $breakdownsByCategory['equipment'] ?? 0,
                'facility' => $breakdownsByCategory['facility'] ?? 0,
                'utility' => $breakdownsByCategory['utility'] ?? 0,
            ]
        ];

        return view('admin.dashboard', compact(
            'stats', 
            'infrastructures', 
            'allInfrastructures', 
            'recentLogs', 
            'allActiveBreakdowns', 
            'areaName', 
            'chartData',
            'frequentInfrastructures',
            'frequentEntities',
            'urgentBreakdowns',
            'allEntities',
            'filterEntity',
            'filterCategory'
        ));
    }
}
