<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'breakdown_log_id',
        'old_status',
        'new_status',
        'note',
        'user_id'
    ];

    public function breakdownLog()
    {
        return $this->belongsTo(BreakdownLog::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
