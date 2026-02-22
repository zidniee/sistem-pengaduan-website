<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyFact extends Model
{
    protected $table = 'daily_facts';

    //
    public function platform() {
        return $this->belongsTo(Platforms::class, 'platform_id');
    }
    
}
