<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'infrastructure_id',
        'title',
        'description',
        'scheduled_date',
        'status',
        'created_by'
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function infrastructure()
    {
        return $this->belongsTo(Infrastructure::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
