<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inspections extends Model
{
    use HasFactory;

    protected $table = 'inspections';

    protected $guarded = [];

    protected $casts = [
        'inspected_at' => 'datetime',
    ];

    protected $fillable = [
    'complaint_id', 
    'user_id', 
    'old_status', 
    'new_status', 
    'account_status',
    'ticket', 
    'inspected_at'
    ];

    // Relate inspections with complaints
    public function complaint() {
        return $this->belongsTo(Complaints::class, 'complaint_id');
    }

    // Relate inspections with users
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
