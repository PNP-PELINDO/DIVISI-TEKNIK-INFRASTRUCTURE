<?php

namespace App\Http\Controllers;

use App\Models\BreakdownLog;
use App\Models\Infrastructure;
use App\Models\Entity;
use App\Models\StatusHistory;
use App\Http\Requests\StoreBreakdownLogRequest;
use App\Http\Requests\UpdateBreakdownLogRequest;
use App\Helpers\ResponseMessage;
use App\Mail\BreakdownReportedMail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class BreakdownLogController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', BreakdownLog::class);
        $user = auth()->user();
        $filterInfraId = request('infrastructure_id');
        $search = request('search');
        $filterEntity = request('entity_id');
        $filterStatus = request('repair_status');

        // JIKA YANG LOGIN ADALAH SUPERADMIN (TAMPILAN RIWAYAT LOG GLOBAL)
        if ($user->role === 'superadmin') {
            $query = BreakdownLog::with(['infrastructure' => fn($q) => $q->withTrashed()->with('entity'), 'createdBy', 'updatedBy', 'statusHistories']);

            if ($filterInfraId) {
                $query->where('infrastructure_id', $filterInfraId);
            }

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('issue_detail', 'like', "%{$search}%")
                      ->orWhereHas('infrastructure', function($sq) use ($search) {
                          $sq->where('code_name', 'like', "%{$search}%")
                             ->orWhere('type', 'like', "%{$search}%")
                             ->orWhere('category', 'like', "%{$search}%")
                             ->orWhereHas('entity', fn($esq) => $esq->where('name', 'like', "%{$search}%"));
                      });
                });
            }

            if ($filterEntity && $filterEntity !== 'all') {
                $query->whereHas('infrastructure', fn($q) => $q->where('entity_id', $filterEntity));
            }

            if ($filterStatus && $filterStatus !== 'all') {
                $query->where('repair_status', $filterStatus);
            }

            $logs = $query->latest()->paginate(20)->withQueryString();

            $activeBreakdowns = BreakdownLog::with(['infrastructure' => fn($q) => $q->withTrashed()->with('entity')])
                ->where('repair_status', '!=', 'resolved')
                ->latest()
                ->get()
                ->keyBy('infrastructure_id');

            $allEntities = Entity::orderBy('name')->get();

            $infraQuery = Infrastructure::with('entity');

            if ($search) {
                $infraQuery->where(function($q) use ($search) {
                    $q->where('code_name', 'like', "%{$search}%")
                      ->orWhere('type', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%")
                      ->orWhereHas('entity', fn($esq) => $esq->where('name', 'like', "%{$search}%"));
                });
            }

            if ($filterEntity && $filterEntity !== 'all') {
                $infraQuery->where('entity_id', $filterEntity);
            }

            if ($filterStatus && $filterStatus !== 'all') {
                $infraQuery->where('status', $filterStatus === 'resolved' ? 'available' : 'breakdown');
            }

            $allInfrastructures = $infraQuery->latest()->get();

            return view('admin.breakdowns.index_admin', compact('logs', 'activeBreakdowns', 'allEntities', 'allInfrastructures'));
        }

        // JIKA YANG LOGIN ADALAH OPERATOR (TAMPILAN EXCEL KESIAPAN ALAT)
        else {
            $infraBaseQuery = Infrastructure::where('entity_id', $user->entity_id);

            // Statistik Akurat (Global untuk Entitas ini)
            $stats = [
                'total' => (clone $infraBaseQuery)->count(),
                'breakdown' => (clone $infraBaseQuery)->where('status', 'breakdown')->count(),
                'available' => (clone $infraBaseQuery)->where('status', 'available')->count(),
            ];
            $stats['readiness_rate'] = $stats['total'] > 0 ? round(($stats['available'] / $stats['total']) * 100, 1) : 0;

            $infraQuery = Infrastructure::with('entity')
                ->where('entity_id', $user->entity_id)
                ->where('status', 'available');

            if ($filterInfraId) {
                $infraQuery->where('id', $filterInfraId);
            }

            if ($search) {
                $infraQuery->where(function($q) use ($search) {
                    $q->where('code_name', 'like', "%{$search}%")
                      ->orWhere('type', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%")
                      ->orWhereHas('entity', fn($esq) => $esq->where('name', 'like', "%{$search}%"));
                });
            }

            if ($filterStatus && $filterStatus !== 'all') {
                $infraQuery->where('status', $filterStatus === 'resolved' ? 'available' : 'breakdown');
            }

            $infrastructures = $infraQuery->latest()->paginate(15, ['*'], 'infra_page')->withQueryString();

            $activeQuery = BreakdownLog::where('repair_status', '!=', 'resolved')
                ->whereHas('infrastructure', function($q) use ($user) {
                    $q->where('entity_id', $user->entity_id);
                });

            if ($filterInfraId) {
                $activeQuery->where('infrastructure_id', $filterInfraId);
            }

            $activeBreakdowns = $activeQuery->get()->keyBy('infrastructure_id');

            $recentQuery = BreakdownLog::with(['infrastructure' => fn($q) => $q->withTrashed()->with('entity')])
                ->where('repair_status', '!=', 'resolved')
                ->whereHas('infrastructure', function($q) use ($user, $search) {
                    $q->where('entity_id', $user->entity_id);
                    if ($search) {
                        $q->where(function($sq) use ($search) {
                            $sq->where('code_name', 'like', "%{$search}%")
                               ->orWhere('type', 'like', "%{$search}%")
                               ->orWhere('category', 'like', "%{$search}%");
                        });
                    }
                });

            if ($search) {
                $recentQuery->orWhere(function($q) use ($search, $user) {
                    $q->where('repair_status', '!=', 'resolved')
                      ->whereHas('infrastructure', fn($sq) => $sq->where('entity_id', $user->entity_id))
                      ->where('issue_detail', 'like', "%{$search}%");
                });
            }

            $recentBreakdowns = $recentQuery->latest()->get();

            // TAMBAHAN: Ambil SEMUA LOG (History) untuk Operator agar bisa melihat riwayat
            $historyQuery = BreakdownLog::with(['infrastructure' => fn($q) => $q->withTrashed(), 'createdBy', 'updatedBy', 'statusHistories'])
                ->whereHas('infrastructure', function($q) use ($user, $search) {
                    $q->where('entity_id', $user->entity_id);
                    if ($search) {
                        $q->where(function($sq) use ($search) {
                            $sq->where('code_name', 'like', "%{$search}%")
                               ->orWhere('type', 'like', "%{$search}%")
                               ->orWhere('category', 'like', "%{$search}%")
                               ->orWhereHas('entity', fn($esq) => $esq->where('name', 'like', "%{$search}%"));
                        });
                    }
                });

            if ($search) {
                $historyQuery->orWhere(function($q) use ($search, $user) {
                    $q->whereHas('infrastructure', fn($sq) => $sq->where('entity_id', $user->entity_id))
                      ->where('issue_detail', 'like', "%{$search}%");
                });
            }

            $historyLogs = $historyQuery->latest()
                ->paginate(15, ['*'], 'history_page')
                ->withQueryString();

            return view('admin.breakdowns.index_operator', compact('infrastructures', 'activeBreakdowns', 'recentBreakdowns', 'historyLogs', 'stats'));
        }
    }

    public function create()
    {
        $user = auth()->user();

        // Hanya ambil infrastruktur yang statusnya available (beroperasi)
        if ($user->role === 'superadmin') {
            $infrastructures = Infrastructure::with('entity')->where('status', 'available')->get();
        } else {
            $infrastructures = Infrastructure::with('entity')
                ->where('entity_id', $user->entity_id)
                ->where('status', 'available')
                ->get();
        }

        return view('admin.breakdowns.create', compact('infrastructures'));
    }

    public function store(StoreBreakdownLogRequest $request)
    {
        $user = auth()->user();

        // Get the infrastructure and validate it exists
        $infrastructure = Infrastructure::findOrFail($request->infrastructure_id);

        // Authorization check: Operator cannot create breakdown for other entity's infrastructure
        if ($user->role !== 'superadmin' && $infrastructure->entity_id !== $user->entity_id) {
            abort(403, 'Unauthorized: Anda tidak bisa membuat laporan untuk aset di cabang lain.');
        }

        // Prevent duplicate active tickets
        $activeTicketExists = BreakdownLog::where('infrastructure_id', $infrastructure->id)
            ->where('repair_status', '!=', 'resolved')
            ->exists();

        if ($activeTicketExists) {
            return redirect()->back()->withErrors(['infrastructure_id' => 'Aset ini sedang dalam status Breakdown dan memiliki laporan perbaikan yang belum selesai.'])->withInput();
        }

        $initialStatus = $request->repair_status ?? 'reported';

        // 1. Catat ke Log Kerusakan. Gunakan status awal sesuai input form.
        $log = BreakdownLog::create([
            'infrastructure_id' => $request->infrastructure_id,
            'issue_detail' => $request->issue_detail,
            'repair_status' => $initialStatus,
            'vendor_pic' => $request->vendor_pic,
            'breakdown_date' => $request->breakdown_date ?? now(),
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        // 2. Ubah status alat menjadi breakdown
        Infrastructure::where('id', $request->infrastructure_id)->update(['status' => 'breakdown']);

        // 3. Record Audit Trail
        StatusHistory::create([
            'breakdown_log_id' => $log->id,
            'new_status' => $initialStatus,
            'user_id' => $user->id,
            'note' => 'Laporan awal dibuat'
        ]);

        // 4. Send Email Notification
        try {
            Mail::to('kardikoanando234@gmail.com')->send(new BreakdownReportedMail($log->load(['infrastructure.entity', 'createdBy'])));
        } catch (\Exception $e) {
            // Log error but don't stop the process
            \Log::error("Gagal mengirim email breakdown: " . $e->getMessage());
        }

        return redirect()->back()->with('success', ResponseMessage::BREAKDOWN_CREATED);
    }

    public function update(UpdateBreakdownLogRequest $request, $id)
    {
        $user = auth()->user();
        $log = BreakdownLog::with('infrastructure')->findOrFail($id);

        $this->authorize('update', $log);

        $oldStatus = $log->repair_status;

        // 2. Ambil semua request data kecuali token form
        $dataToUpdate = $request->except(['_token', '_method']);

        // Add audit trail
        $dataToUpdate['updated_by'] = $user->id;

        // Workflow Validation: Allowing flexible forward progression
        $statusOrder = ['reported' => 1, 'order_part' => 2, 'on_progress' => 3, 'resolved' => 4];

        if (isset($request->repair_status)) {
            $currentStatus = $log->repair_status;
            $newStatus = $request->repair_status;

            // Prevent going backward in status (optional, but usually good for integrity)
            if ($statusOrder[$newStatus] < $statusOrder[$currentStatus]) {
                // Allow going back if it's not resolved yet?
                // For now, let's just allow all forward moves and prevent illegal jumps if any.
                // Actually, let's just remove the restrictive order and let users decide,
                // but keep the resolved status as final.
            }
        }

        // 3. Logika Upload Bukti Fisik
        if ($request->hasFile('document_proof')) {
            $file = $request->file('document_proof');
            // Generate secure random filename to avoid path guessing
            $ext = $file->getClientOriginalExtension();
            $filename = 'proof_' . Str::uuid() . '.' . $ext;
            $path = 'assets/proofs/' . $filename;

            // Delete previous file if exists
            if (!empty($log->document_proof) && Storage::disk('public')->exists($log->document_proof)) {
                try { Storage::disk('public')->delete($log->document_proof); } catch (\Exception $e) { \Log::warning('Failed to delete old proof: '.$e->getMessage()); }
            }

            // Store new file on public disk (consider private disk in production)
            $stored = $file->storeAs('assets/proofs', $filename, 'public');
            $dataToUpdate['document_proof'] = $stored;
        }

        if ($request->repair_status === 'resolved') {
            $dataToUpdate['resolved_date'] = $request->resolved_date ?: now();
        }

        // 4. Update data ke database (Status dan Deretan Tanggal)
        $log->update($dataToUpdate);

        // 5. Record Audit Trail if status changed
        if (isset($request->repair_status) && $request->repair_status !== $oldStatus) {
            StatusHistory::create([
                'breakdown_log_id' => $log->id,
                'old_status' => $oldStatus,
                'new_status' => $request->repair_status,
                'user_id' => $user->id,
                'note' => 'Update status perbaikan'
            ]);
        }

        // 6. Jika status perbaikan "resolved", kembalikan status alat jadi "available"
        if ($request->repair_status === 'resolved') {
            $log->infrastructure->update(['status' => 'available']);
            return redirect()->back()->with('success', ResponseMessage::BREAKDOWN_RESOLVED);
        } else {
            $log->infrastructure->update(['status' => 'breakdown']);
        }

        return redirect()->back()->with('success', ResponseMessage::BREAKDOWN_UPDATED);
    }

    public function destroy($id)
    {
        $log = BreakdownLog::findOrFail($id);
        $this->authorize('delete', $log);

        // (Soft Deletes: File bukti fisik tidak dihapus dari storage agar tetap tersedia jika data di-restore)

        // CRITICAL FIX: Check if there are other unresolved breakdowns for this infrastructure
        // Only mark as available if no other active breakdowns exist
        $otherActiveBreakdowns = BreakdownLog::where('infrastructure_id', $log->infrastructure_id)
            ->where('id', '!=', $log->id)
            ->where('repair_status', '!=', 'resolved')
            ->count();

        if ($otherActiveBreakdowns === 0) {
            $log->infrastructure->update(['status' => 'available']);
        }

        // Hapus laporan
        $log->delete();

        return redirect()->back()->with('success', ResponseMessage::BREAKDOWN_DELETED);
    }

    /**
     * Download stored document proof (protected by policy).
     */
    public function downloadProof($id)
    {
        $log = BreakdownLog::findOrFail($id);
        $this->authorize('view', $log);

        if (empty($log->document_proof) || !Storage::disk('public')->exists($log->document_proof)) {
            abort(404, 'File bukti tidak ditemukan.');
        }

        return Storage::disk('public')->download($log->document_proof);
    }
}
