<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceSchedule;
use App\Models\Infrastructure;
use Illuminate\Http\Request;

class MaintenanceScheduleController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', MaintenanceSchedule::class);
        $user = auth()->user();
        $query = MaintenanceSchedule::with(['infrastructure.entity', 'creator']);

        if ($user->role !== 'superadmin') {
            $query->whereHas('infrastructure', function($q) use ($user) {
                $q->where('entity_id', $user->entity_id);
            });
        }

        $schedules = $query->latest()->paginate(15);
        
        return view('admin.maintenance.index', compact('schedules'));
    }

    public function create()
    {
        $this->authorize('create', MaintenanceSchedule::class);
        $user = auth()->user();
        $infrastructures = Infrastructure::when($user->role !== 'superadmin', function($q) use ($user) {
            return $q->where('entity_id', $user->entity_id);
        })->get();

        return view('admin.maintenance.create', compact('infrastructures'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', MaintenanceSchedule::class);
        $request->validate([
            'infrastructure_id' => 'required|exists:infrastructures,id',
            'title' => 'required|string|max:255',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'description' => 'nullable|string',
        ]);

        MaintenanceSchedule::create([
            'infrastructure_id' => $request->infrastructure_id,
            'title' => $request->title,
            'description' => $request->description,
            'scheduled_date' => $request->scheduled_date,
            'status' => 'scheduled',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.maintenance.index')->with('success', 'Jadwal pemeliharaan berhasil ditambahkan.');
    }

    public function update(Request $request, MaintenanceSchedule $maintenanceSchedule)
    {
        $this->authorize('update', $maintenanceSchedule);
        $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled'
        ]);

        $maintenanceSchedule->update(['status' => $request->status]);

        return back()->with('success', 'Status jadwal berhasil diperbarui.');
    }

    public function destroy(MaintenanceSchedule $maintenanceSchedule)
    {
        $this->authorize('delete', $maintenanceSchedule);
        $maintenanceSchedule->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}
