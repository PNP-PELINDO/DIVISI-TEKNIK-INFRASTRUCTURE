<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BreakdownLog extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the standardized configuration for repair statuses.
     * Centralized to ensure UI consistency across all modules.
     */
    public static function getStatusConfig()
    {
        return [
            'reported' => [
                'bg' => 'bg-red-50 dark:bg-rose-500/10',
                'text' => 'text-red-600 dark:text-rose-400',
                'border' => 'border-red-200 dark:border-rose-500/20',
                'icon' => 'fa-exclamation-circle',
                'label' => 'Dilaporkan'
            ],
            'troubleshooting' => [
                'bg' => 'bg-orange-50 dark:bg-amber-500/10',
                'text' => 'text-orange-600 dark:text-amber-400',
                'border' => 'border-orange-200 dark:border-amber-500/20',
                'icon' => 'fa-search',
                'label' => 'Troubleshoot'
            ],
            'work_order' => [
                'bg' => 'bg-blue-50 dark:bg-sky-500/10',
                'text' => 'text-blue-600 dark:text-sky-400',
                'border' => 'border-blue-200 dark:border-sky-500/20',
                'icon' => 'fa-file-signature',
                'label' => 'Work Order'
            ],
            'order_part' => [
                'bg' => 'bg-purple-50 dark:bg-indigo-500/10',
                'text' => 'text-purple-600 dark:text-indigo-400',
                'border' => 'border-purple-200 dark:border-indigo-500/20',
                'icon' => 'fa-box-open',
                'label' => 'Order Part'
            ],
            'on_progress' => [
                'bg' => 'bg-amber-50 dark:bg-yellow-500/10',
                'text' => 'text-amber-600 dark:text-yellow-400',
                'border' => 'border-amber-200 dark:border-yellow-500/20',
                'icon' => 'fa-tools',
                'label' => 'Sedang Diperbaiki'
            ],
            'testing' => [
                'bg' => 'bg-indigo-50 dark:bg-violet-500/10',
                'text' => 'text-indigo-600 dark:text-violet-400',
                'border' => 'border-indigo-200 dark:border-violet-500/20',
                'icon' => 'fa-vial',
                'label' => 'Com Test'
            ],
            'resolved' => [
                'bg' => 'bg-emerald-50 dark:bg-emerald-500/10',
                'text' => 'text-emerald-600 dark:text-emerald-400',
                'border' => 'border-emerald-200 dark:border-emerald-500/20',
                'icon' => 'fa-check-circle',
                'label' => 'Selesai'
            ]
        ];
    }

    protected $fillable = [
        'infrastructure_id',
        'issue_detail',
        'repair_status',
        'vendor_pic',
        // Tambahan Kolom Baru:
        'breakdown_date',
        'troubleshoot_date',
        'ba_date',
        'work_order_date',
        'pr_po_date',
        'sparepart_date',
        'start_work_date',
        'com_test_date',
        'resolved_date',
        'document_proof',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'breakdown_date' => 'date',
        'troubleshoot_date' => 'date',
        'ba_date' => 'date',
        'work_order_date' => 'date',
        'pr_po_date' => 'date',
        'sparepart_date' => 'date',
        'start_work_date' => 'date',
        'com_test_date' => 'date',
        'resolved_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi ke tabel Infrastruktur
    public function infrastructure()
    {
        return $this->belongsTo(Infrastructure::class)->withTrashed();
    }

    /**
     * Relasi ke tabel users untuk audit trail
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function statusHistories()
    {
        return $this->hasMany(StatusHistory::class)->with('user')->latest();
    }
}
