<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entity;
use App\Models\Infrastructure;
use App\Models\BreakdownLog;

class LandingController extends Controller
{
    /**
     * Display the main landing page with dashboard overview.
     */
    public function index()
    {
        // 1. Ambil data infrastruktur dengan select kolom secukupnya untuk menghemat memori
        // Hanya yang dibutuhkan di view: id, code_name, status, type, category, entity_id
        $infrastructures = Infrastructure::select('id', 'code_name', 'status', 'type', 'category', 'entity_id')->get();

        // 2. Ambil entitas beserta relasi infrastruktur (dioptimalkan dengan query select yang spesifik jika bisa, 
        // tapi untuk saat ini menggunakan pola yang sama agar tidak break frontend)
        $entities = Entity::with(['infrastructures' => function($query) {
            $query->select('id', 'code_name', 'status', 'type', 'category', 'image', 'entity_id');
        }])->get();

        // 3. Ambil log insiden aktif (belum resolved) dengan eager loading yang efisien
        $breakdowns = BreakdownLog::with([
            'infrastructure' => fn($q) => $q->withTrashed()->select('id', 'code_name', 'type', 'category', 'entity_id')->with('entity:id,name')
        ])
        ->where('repair_status', '!=', 'resolved')
        ->orderBy('created_at', 'desc')
        ->get();

        return view('welcome', compact('infrastructures', 'entities', 'breakdowns'));
    }
}
