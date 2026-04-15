<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use Illuminate\Http\Request;

class EntityController extends Controller
{
    // Menampilkan daftar entitas
    public function index()
    {
        // Mengambil semua entitas beserta jumlah alat yang dimilikinya
        $entities = Entity::withCount('infrastructures')->latest()->get();
        return view('admin.entities.index', compact('entities'));
    }

    // Menampilkan form tambah entitas
    public function create()
    {
        return view('admin.entities.create');
    }

    // Menyimpan data entitas baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:entities,code',
        ], [
            'code.unique' => 'Kode entitas ini sudah digunakan, silakan gunakan kode lain.'
        ]);

        Entity::create($request->all());

        return redirect()->route('admin.entities.index')
            ->with('success', 'Entitas pelabuhan baru berhasil didaftarkan.');
    }

    // Menampilkan form edit entitas
    public function edit(Entity $entity)
    {
        return view('admin.entities.edit', compact('entity'));
    }

    // Menyimpan perubahan data entitas
    public function update(Request $request, Entity $entity)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:entities,code,' . $entity->id,
        ]);

        $entity->update($request->all());

        return redirect()->route('admin.entities.index')
            ->with('success', 'Data entitas berhasil diperbarui.');
    }

    // Menghapus entitas
    public function destroy(Entity $entity)
    {
        // Proteksi: Jangan izinkan hapus jika masih ada alat yang terikat ke entitas ini
        if ($entity->infrastructures()->count() > 0) {
            return redirect()->route('admin.entities.index')
                ->with('error', 'Gagal dihapus! Entitas ini masih memiliki data infrastruktur yang terdaftar.');
        }
        
        $entity->delete();

        return redirect()->route('admin.entities.index')
            ->with('success', 'Entitas pelabuhan telah dihapus dari sistem.');
    }
}
