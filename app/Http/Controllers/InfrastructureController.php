<?php

namespace App\Http\Controllers;

use App\Models\Infrastructure;
use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InfrastructureController extends Controller 
{
    public function index() 
    {
        $infrastructures = Infrastructure::with('entity')->latest()->get();
        return view('admin.infrastructures.index', compact('infrastructures'));
    }

    public function create() 
    {
        $entities = Entity::all();
        return view('admin.infrastructures.create', compact('entities'));
    }

    // INI BAGIAN YANG HARUS DIPERBAIKI
    public function store(Request $request) 
    {
        // 1. Validasi input, tambahkan validasi untuk 'image'
        $request->validate([
            'entity_id' => 'required',
            'category'  => 'required',
            'type'      => 'required',
            'code_name' => 'required|unique:infrastructures',
            'status'    => 'required',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048' // Pastikan ini ada
        ]);

        // 2. Ambil semua data request
        $data = $request->all();

        // 3. Logika untuk menangkap dan memindahkan file gambar
        if ($request->hasFile('image')) {
            // Ini akan menyimpan gambar ke folder: storage/app/public/assets/infrastructures
            // dan mengembalikan string path-nya (contoh: 'assets/infrastructures/xyz.jpg')
            $imagePath = $request->file('image')->store('assets/infrastructures', 'public');
            
            // Masukkan path gambar ke dalam array data untuk disimpan ke database
            $data['image'] = $imagePath;
        }

        // 4. Simpan ke database
        Infrastructure::create($data);

        return redirect()->route('admin.infrastructures.index')->with('success', 'Aset baru beserta fotonya berhasil ditambahkan.');
    }

    public function edit(Infrastructure $infrastructure)
    {
        $entities = Entity::all();
        return view('admin.infrastructures.edit', compact('infrastructure', 'entities'));
    }

    // UPDATE JUGA BAGIAN INI AGAR GAMBAR BISA DIEDIT
    public function update(Request $request, Infrastructure $infrastructure)
    {
        $request->validate([
            'entity_id' => 'required',
            'category'  => 'required',
            'type'      => 'required',
            'code_name' => 'required|unique:infrastructures,code_name,' . $infrastructure->id,
            'status'    => 'required',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Jika ada gambar lama, hapus dulu dari server agar tidak menumpuk
            if ($infrastructure->image) {
                Storage::disk('public')->delete($infrastructure->image);
            }
            // Simpan gambar baru
            $data['image'] = $request->file('image')->store('assets/infrastructures', 'public');
        }

        $infrastructure->update($data);

        return redirect()->route('admin.infrastructures.index')
            ->with('success', 'Data aset berhasil diperbarui.');
    }

    public function destroy(Infrastructure $infrastructure) 
    {
        // Hapus file fisik gambar dari folder storage sebelum datanya dihapus dari database
        if ($infrastructure->image) {
            Storage::disk('public')->delete($infrastructure->image);
        }

        $infrastructure->delete();

        return redirect()->route('admin.infrastructures.index')->with('success', 'Aset berhasil dihapus.');
    }
}
