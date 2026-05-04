<?php

namespace App\Http\Controllers;

use App\Models\Infrastructure;
use App\Models\Entity;
use App\Http\Requests\StoreInfrastructureRequest;
use App\Http\Requests\UpdateInfrastructureRequest;
use App\Helpers\ResponseMessage;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Storage;

class InfrastructureController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Infrastructure::class);
        $user = auth()->user();

        $query = Infrastructure::with(['entity', 'createdBy', 'updatedBy', 'breakdownLogs.createdBy']);

        // Filter berdasarkan peran
        if ($user->role !== 'superadmin') {
            $query->where('entity_id', $user->entity_id);
        }

        // Search Logic
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('code_name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Filter Logic
        if (request('entity_id') && request('entity_id') !== 'all') {
            $query->where('entity_id', request('entity_id'));
        }

        if (request('category') && request('category') !== 'all') {
            $query->where('category', request('category'));
        }

        if (request('status') && request('status') !== 'all') {
            $query->where('status', request('status'));
        }

        $infrastructures = $query->latest()->paginate(20)->withQueryString();

        // Ambil data unik untuk filter dropdown
        if ($user->role === 'superadmin') {
            $allEntities = Entity::orderBy('name')->get();
        } else {
            $allEntities = collect();
        }

        return view('admin.infrastructures.index', compact('infrastructures', 'allEntities'));
    }

    public function create()
    {
        $this->authorize('create', Infrastructure::class);
        $user = auth()->user();

        // JIKA SUPERADMIN: Bisa pilih semua cabang. JIKA OPERATOR: Hanya cabang dia sendiri.
        if ($user->role === 'superadmin') {
            $entities = Entity::all();
        } else {
            $entities = Entity::where('id', $user->entity_id)->get();
        }

        // Mengambil daftar tipe alat unik berdasarkan kategori untuk dropdown
        $typeCategoryMap = Infrastructure::select('type', 'category')
                            ->distinct()
                            ->pluck('category', 'type')
                            ->toArray();

        return view('admin.infrastructures.create', compact('entities', 'typeCategoryMap'));
    }

    public function store(StoreInfrastructureRequest $request)
    {
        $this->authorize('create', Infrastructure::class);
        $user = auth()->user();

        $validated = $request->validated();

        // 2. Proteksi Entity ID dengan validation (CRITICAL FIX)
        // Jika superadmin, entity_id wajib dari form. Jika operator, entity_id dipaksa dari sistem (keamanan).
        if ($user->role === 'superadmin') {
            $targetEntityId = $request->entity_id;
        } else {
            $targetEntityId = $user->entity_id;
        }

        // 3. Logika Tipe Alat (Dropdown vs Input Baru)
        $finalType = $request->type_select === 'new' ? $request->type_new : $request->type_select;

        if (empty($finalType)) {
            return back()->withErrors(['type_new' => 'Jenis alat wajib diisi!'])->withInput();
        }

        // 4. Proses Upload Gambar
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'infra_' . preg_replace('/[^A-Za-z0-9\-]/', '', $validated['code_name']) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $imagePath = ImageHelper::compressAndSave($file, 'assets/infrastructures', $filename, 60);
        }

        // 5. Simpan ke Database dengan audit trail
        Infrastructure::create([
            'entity_id' => $targetEntityId,
            'category'  => $validated['category'],
            'type'      => $finalType,
            'code_name' => $validated['code_name'],
            'status'    => $validated['status'],
            'quantity'  => 1,
            'image'     => $imagePath,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        return redirect()->route('admin.infrastructures.index')
            ->with('success', ResponseMessage::INFRASTRUCTURE_CREATED);
    }

    public function show(Infrastructure $infrastructure)
    {
        // Redirect ke halaman Log Kerusakan dengan parameter filter agar user bisa langsung mengupdate status perbaikannya
        return redirect()->route('admin.breakdowns.index', ['infrastructure_id' => $infrastructure->id]);
    }

    public function edit(Infrastructure $infrastructure)
    {
        $this->authorize('update', $infrastructure);
        $user = auth()->user();

        if ($user->role === 'superadmin') {
            $entities = Entity::all();
        } else {
            $entities = Entity::where('id', $user->entity_id)->get();
        }

        $typeCategoryMap = Infrastructure::select('type', 'category')
                            ->distinct()
                            ->pluck('category', 'type')
                            ->toArray();

        return view('admin.infrastructures.edit', compact('infrastructure', 'entities', 'typeCategoryMap'));
    }

    public function update(UpdateInfrastructureRequest $request, Infrastructure $infrastructure)
    {
        $this->authorize('update', $infrastructure);
        $user = auth()->user();

        $validated = $request->validated();

        // 2. Tentukan Entity ID
        // Operator tidak akan bisa mengubah entity_id meskipun form diakali (inspect element)
        $targetEntityId = $user->role === 'superadmin' ? $request->entity_id : $infrastructure->entity_id;

        // 3. Logika Tipe Alat
        $finalType = $request->type_select === 'new' ? $request->type_new : $request->type_select;

        // Jika type menggunakan form lama yang tidak punya select, pakai request->type langsung
        if (empty($finalType) && $request->filled('type')) {
            $finalType = $request->type;
        }

        if (empty($finalType)) {
            return back()->withErrors(['type_new' => 'Jenis alat wajib diisi!'])->withInput();
        }

        // 4. Siapkan array data untuk diupdate (Lebih aman dari Mass Assignment)
        $dataToUpdate = [
            'entity_id' => $targetEntityId,
            'category'  => $validated['category'],
            'type'      => $finalType,
            'code_name' => $validated['code_name'],
            'status'    => $validated['status'],
            'quantity'  => 1,
        ];

        // 5. Proses Ganti Foto
        if ($request->hasFile('image')) {
            // Cek dan hapus foto lama dari brankas storage agar tidak penuh
            if ($infrastructure->image && Storage::disk('public')->exists($infrastructure->image)) {
                Storage::disk('public')->delete($infrastructure->image);
            }
            // Simpan foto baru dengan kompresi
            $file = $request->file('image');
            $filename = 'infra_' . preg_replace('/[^A-Za-z0-9\-]/', '', $validated['code_name']) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $dataToUpdate['image'] = ImageHelper::compressAndSave($file, 'assets/infrastructures', $filename, 60);
        }

        // Add audit trail
        $dataToUpdate['updated_by'] = $user->id;

        // 6. Eksekusi Update
        $infrastructure->update($dataToUpdate);

        return redirect()->route('admin.infrastructures.index')
            ->with('success', ResponseMessage::INFRASTRUCTURE_UPDATED);
    }

    public function destroy(Infrastructure $infrastructure)
    {
        $this->authorize('delete', $infrastructure);

        // (Soft Deletes: File bukti fisik gambar tidak dihapus dari storage agar tetap tersedia jika data di-restore)

        $infrastructure->delete();

        return redirect()->route('admin.infrastructures.index')
            ->with('success', ResponseMessage::INFRASTRUCTURE_DELETED_WITH_FILE);
    }
}
