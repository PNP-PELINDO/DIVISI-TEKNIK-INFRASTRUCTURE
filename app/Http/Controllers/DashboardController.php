<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\Infrastructure;
use App\Models\BreakdownLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Utama untuk Card
        $stats = [
            'total' => Infrastructure::count(),
            'available' => Infrastructure::where('status', 'available')->count(),
            'breakdown' => Infrastructure::where('status', 'breakdown')->count(),
        ];

        // Data Aset dipisahkan berdasarkan Kategori untuk Tab
        $equipment = Infrastructure::with('entity')->where('category', 'equipment')->get();
        $facility = Infrastructure::with('entity')->where('category', 'facility')->get();
        $utility = Infrastructure::with('entity')->where('category', 'utility')->get();

        // Data Log Insiden yang belum 'resolved'
        $recent_logs = BreakdownLog::with('infrastructure.entity')
            ->where('repair_status', '!=', 'resolved')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'equipment', 'facility', 'utility', 'recent_logs'));
    }
}
