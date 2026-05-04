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

        // Filter berdasarkan peran
        if ($user->role !== 'superadmin') {
            $query->whereHas('infrastructure', function($q) use ($user) {
                $q->where('entity_id', $user->entity_id);
            });
        }

        // Search Logic
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('infrastructure', function($sq) use ($search) {
                      $sq->where('code_name', 'like', "%{$search}%")
                         ->orWhere('type', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Logic
        if ($user->role === 'superadmin' && request('entity_id') && request('entity_id') !== 'all') {
            $query->whereHas('infrastructure', fn($q) => $q->where('entity_id', request('entity_id')));
        }

        if (request('status') && request('status') !== 'all') {
            $query->where('status', request('status'));
        }

        $schedules = $query->latest()->paginate(15)->withQueryString();
        
        $allEntities = $user->role === 'superadmin' ? \App\Models\Entity::orderBy('name')->get() : collect();
        
        return view('admin.maintenance.index', compact('schedules', 'allEntities'));
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

    public function edit(MaintenanceSchedule $maintenance)
    {
        $this->authorize('update', $maintenance);
        $user = auth()->user();
        $infrastructures = Infrastructure::when($user->role !== 'superadmin', function($q) use ($user) {
            return $q->where('entity_id', $user->entity_id);
        })->get();

        return view('admin.maintenance.edit', compact('maintenance', 'infrastructures'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', MaintenanceSchedule::class);
        $user = auth()->user();

        $request->validate([
            'infrastructure_id' => [
                'required',
                'exists:infrastructures,id',
                function ($attribute, $value, $fail) use ($user) {
                    if ($user->role !== 'superadmin') {
                        $infra = Infrastructure::find($value);
                        if (!$infra || $infra->entity_id !== $user->entity_id) {
                            $fail('Anda tidak memiliki wewenang untuk mendaftarkan jadwal pada aset ini.');
                        }
                    }
                },
            ],
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
            'created_by' => $user->id,
        ]);

        return redirect()->route('admin.maintenance.index')->with('success', 'Jadwal pemeliharaan berhasil ditambahkan.');
    }

    public function update(Request $request, MaintenanceSchedule $maintenance)
    {
        $this->authorize('update', $maintenance);
        $user = auth()->user();

        // Jika hanya update status (dari form di index)
        if ($request->has('status') && !$request->has('title')) {
            $request->validate([
                'status' => 'required|in:scheduled,completed,cancelled'
            ]);
            $maintenance->update(['status' => $request->status]);
            return back()->with('success', 'Status jadwal berhasil diperbarui.');
        }

        // Jika update full (dari form edit)
        $request->validate([
            'infrastructure_id' => [
                'required',
                'exists:infrastructures,id',
                function ($attribute, $value, $fail) use ($user) {
                    if ($user->role !== 'superadmin') {
                        $infra = Infrastructure::find($value);
                        if (!$infra || $infra->entity_id !== $user->entity_id) {
                            $fail('Anda tidak memiliki wewenang untuk aset ini.');
                        }
                    }
                },
            ],
            'title' => 'required|string|max:255',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'description' => 'nullable|string',
            'status' => 'required|in:scheduled,completed,cancelled'
        ]);

        $maintenance->update($request->all());

        return redirect()->route('admin.maintenance.index')->with('success', 'Jadwal pemeliharaan berhasil diperbarui.');
    }

    public function destroy(MaintenanceSchedule $maintenanceSchedule)
    {
        $this->authorize('delete', $maintenanceSchedule);
        $maintenanceSchedule->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}
