<?php

namespace App\Http\Controllers;

use App\Models\BreakdownLog;
use App\Models\Infrastructure;
use Illuminate\Http\Request;

class BreakdownLogController extends Controller
{
    // 1. Menampilkan Daftar Log Kerusakan
    public function index()
    {
        $logs = BreakdownLog::with('infrastructure.entity')->latest()->get();
        return view('admin.breakdowns.index', compact('logs'));
    }

    // 2. Menampilkan Form Lapor Kerusakan Baru
    public function create()
    {
        // Hanya tampilkan alat yang statusnya 'available' (beroperasi) untuk dilaporkan rusak
        $infrastructures = Infrastructure::where('status', 'available')->get();
        return view('admin.breakdowns.create', compact('infrastructures'));
    }

    // 3. Menyimpan Laporan Baru & Mengubah Status Alat
    public function store(Request $request)
    {
        $request->validate([
            'infrastructure_id' => 'required|exists:infrastructures,id',
            'issue_detail' => 'required|string',
            'vendor_pic' => 'nullable|string',
            'repair_status' => 'required',
        ]);

        $log = BreakdownLog::create($request->all());

        // OTOMATIS: Ubah status alat menjadi breakdown (Terkendala)
        $log->infrastructure->update(['status' => 'breakdown']);

        return redirect()->route('admin.breakdowns.index')
            ->with('success', 'Laporan kerusakan berhasil dibuat dan status aset telah diubah.');
    }

    // 4. Menampilkan Form Update Progres Perbaikan
    public function edit(BreakdownLog $breakdown)
    {
        // Pastikan relasi infrastruktur dimuat agar namanya bisa ditampilkan di form
        $breakdown->load('infrastructure.entity');
        return view('admin.breakdowns.edit', compact('breakdown'));
    }

    // 5. Menyimpan Update Progres & Mengembalikan Status Alat jika Selesai
    public function update(Request $request, BreakdownLog $breakdown)
    {
        $request->validate([
            'issue_detail' => 'required|string',
            'vendor_pic' => 'nullable|string',
            'repair_status' => 'required',
        ]);

        $breakdown->update($request->only(['repair_status', 'vendor_pic', 'issue_detail']));

        // OTOMATIS: Jika status diubah menjadi selesai (resolved)
        if ($request->repair_status == 'resolved') {
            $breakdown->infrastructure->update(['status' => 'available']);
            $breakdown->update(['resolved_at' => now()]);
        }

        return redirect()->route('admin.breakdowns.index')
            ->with('success', 'Status progres perbaikan berhasil diperbarui.');
    }

    // 6. Menghapus Laporan Kerusakan
    public function destroy(BreakdownLog $breakdown)
    {
        // OTOMATIS: Jika laporan dihapus karena salah input, 
        // dan status alatnya masih 'breakdown', kembalikan ke 'available'
        if ($breakdown->repair_status != 'resolved') {
            $breakdown->infrastructure->update(['status' => 'available']);
        }
        
        $breakdown->delete();

        return redirect()->route('admin.breakdowns.index')
            ->with('success', 'Laporan insiden berhasil dihapus dari sistem.');
    }
}
