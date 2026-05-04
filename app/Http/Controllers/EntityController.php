<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Helpers\ResponseMessage;
use Illuminate\Http\Request;

class EntityController extends Controller
{
    // Menampilkan daftar entitas
    public function index()
    {
        $this->authorize('viewAny', Entity::class);
        $query = Entity::withCount('infrastructures');

        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $entities = $query->latest()->paginate(15)->withQueryString();
        return view('admin.entities.index', compact('entities'));
    }

    // Menampilkan form tambah entitas
    public function create()
    {
        $this->authorize('create', Entity::class);
        return view('admin.entities.create');
    }

    // Menyimpan data entitas baru
    public function store(Request $request)
    {
        $this->authorize('create', Entity::class);
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:entities,code',
        ], [
            'code.unique' => 'Kode entitas ini sudah digunakan, silakan gunakan kode lain.'
        ]);

        Entity::create($request->all());

        return redirect()->route('admin.entities.index')
            ->with('success', ResponseMessage::ENTITY_CREATED);
    }

    // Menampilkan form edit entitas
    public function edit(Entity $entity)
    {
        $this->authorize('update', $entity);
        return view('admin.entities.edit', compact('entity'));
    }

    // Menyimpan perubahan data entitas
    public function update(Request $request, Entity $entity)
    {
        $this->authorize('update', $entity);
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:entities,code,' . $entity->id,
        ]);

        $entity->update($request->all());

        return redirect()->route('admin.entities.index')
            ->with('success', ResponseMessage::ENTITY_UPDATED);
    }

    // Menghapus entitas
    public function destroy(Entity $entity)
    {
        $this->authorize('delete', $entity);
        // Proteksi: Jangan izinkan hapus jika masih ada alat yang terikat ke entitas ini
        if ($entity->infrastructures()->count() > 0) {
            return redirect()->route('admin.entities.index')
                ->with('error', ResponseMessage::ENTITY_HAS_INFRASTRUCTURE);
        }

        $entity->delete();

        return redirect()->route('admin.entities.index')
            ->with('success', ResponseMessage::ENTITY_DELETED);
    }
}
