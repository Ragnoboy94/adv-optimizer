<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdStat extends Model
{
    protected $fillable = ['ad_id', 'ts', 'views', 'clicks', 'spent'];
    protected $casts = ['ts' => 'datetime'];

    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }
}
