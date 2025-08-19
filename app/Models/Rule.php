<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rule extends Model
{
    protected $fillable = ['name', 'is_active', 'scope_ad_id', 'evaluation_window_minutes', 'condition_tree', 'actions'];
    protected $casts = ['is_active' => 'boolean', 'condition_tree' => 'array', 'actions' => 'array'];

    public function scopeAd(): BelongsTo
    {
        return $this->belongsTo(Ad::class, 'scope_ad_id');
    }
}
