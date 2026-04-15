<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BreakdownLog extends Model {
    protected $fillable = ['infrastructure_id', 'issue_detail', 'vendor_pic', 'repair_status', 'resolved_at'];

    public function infrastructure() {
        return $this->belongsTo(Infrastructure::class);
    }
}
