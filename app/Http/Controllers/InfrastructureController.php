<?php

namespace App\Http\Controllers;

use App\Models\Infrastructure;
use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class InfrastructureController extends Controller 
{
    public function index() 
    {
        $user = auth()->user();

        // Tampilkan semua untuk superadmin, filter berdasarkan cabang untuk operator
        if ($user->role === 'superadmin') {
            $infrastructures = Infrastructure::with('entity')->latest()->get();
        } else {
            $infrastructures = Infrastructure::with('entity')
                ->where('entity_id', $user->entity_id)
                ->latest()->get();
        }

        return view('admin.infrastructures.index', compact('infrastructures'));
    }

    public function create() 
    {
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

    public function store(Request $request) 
    {
        $user = auth()->user();

        // 1. Validasi dasar
        $validated = $request->validate([
            'category'  => 'required|in:equipment,facility,utility',
            'code_name' => 'required|unique:infrastructures',
            'status'    => 'required|in:available,breakdown',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        // 2. Proteksi Entity ID
        // Jika superadmin, entity_id wajib dari form. Jika operator, entity_id dipaksa dari sistem (keamanan).
        $targetEntityId = $user->role === 'superadmin' ? $request->entity_id : $user->entity_id;

        if ($user->role === 'superadmin' && empty($targetEntityId)) {
            return back()->withErrors(['entity_id' => 'Entitas/Cabang wajib dipilih!'])->withInput();
        }

        // 3. Logika Tipe Alat (Dropdown vs Input Baru)
        $finalType = $request->type_select === 'new' ? $request->type_new : $request->type_select;
        
        if (empty($finalType)) {
            return back()->withErrors(['type_new' => 'Jenis alat wajib diisi!'])->withInput();
        }

        // 4. Proses Upload Gambar
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('assets/infrastructures', 'public');
        }

        // 5. Simpan ke Database
        Infrastructure::create([
            'entity_id' => $targetEntityId, 
            'category'  => $validated['category'],
            'type'      => $finalType,
            'code_name' => $validated['code_name'],
            'status'    => $validated['status'],
            'quantity'  => 1,
            'image'     => $imagePath,
        ]);

        return redirect()->route('admin.infrastructures.index')
            ->with('success', 'Aset baru berhasil didaftarkan di wilayah Anda.');
    }

    public function edit(Infrastructure $infrastructure)
    {
        $user = auth()->user();

        // Proteksi: Operator tidak boleh edit alat dari cabang lain
        if ($user->role !== 'superadmin' && $infrastructure->entity_id !== $user->entity_id) {
            return redirect()->route('admin.infrastructures.index')->with('error', 'Akses ditolak! Ini bukan aset di wilayah Anda.');
        }

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

    public function update(Request $request, Infrastructure $infrastructure)
    {
        $user = auth()->user();

        // Proteksi keamanan di backend
        if ($user->role !== 'superadmin' && $infrastructure->entity_id !== $user->entity_id) {
            abort(403, 'Unauthorized action.');
        }

        // 1. Validasi input
        $validated = $request->validate([
            'category'  => 'required|in:equipment,facility,utility',
            'code_name' => 'required|unique:infrastructures,code_name,' . $infrastructure->id,
            'status'    => 'required|in:available,breakdown',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

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
            // Simpan foto baru dan catat path-nya
            $dataToUpdate['image'] = $request->file('image')->store('assets/infrastructures', 'public');
        }

        // 6. Eksekusi Update
        $infrastructure->update($dataToUpdate);

        return redirect()->route('admin.infrastructures.index')
            ->with('success', 'Data inventaris berhasil diperbarui.');
    }

    public function destroy(Infrastructure $infrastructure) 
    {
        $user = auth()->user();
        
        // Proteksi Hapus
        if ($user->role !== 'superadmin' && $infrastructure->entity_id !== $user->entity_id) {
            return redirect()->route('admin.infrastructures.index')->with('error', 'Akses ditolak!');
        }

        // Hapus file fisik gambar jika ada
        if ($infrastructure->image && Storage::disk('public')->exists($infrastructure->image)) {
            Storage::disk('public')->delete($infrastructure->image);
        }

        $infrastructure->delete();

        return redirect()->route('admin.infrastructures.index')
            ->with('success', 'Aset telah dihapus dari database beserta fotonya.');
    }

    public function deleteAll()
    {
        // Hanya superadmin yang boleh melakukan reset total
        if (auth()->user()->role !== 'superadmin') {
            return back()->with('error', 'Akses ditolak!');
        }

        // Hapus semua file gambar fisik dari direktori
        $infrastructuresWithImages = Infrastructure::whereNotNull('image')->get();
        foreach ($infrastructuresWithImages as $item) {
            if (Storage::disk('public')->exists($item->image)) {
                Storage::disk('public')->delete($item->image);
            }
        }

        // Bersihkan tabel dengan menonaktifkan constraint foreign key sementara
        Schema::disableForeignKeyConstraints();
        \App\Models\BreakdownLog::truncate();
        Infrastructure::truncate();
        Schema::enableForeignKeyConstraints();

        return redirect()->route('admin.infrastructures.index')
            ->with('success', 'Seluruh database infrastruktur beserta file fotonya telah dibersihkan total.');
    }
}
