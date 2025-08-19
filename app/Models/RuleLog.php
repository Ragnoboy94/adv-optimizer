<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RuleLog extends Model
{
    protected $fillable = ['rule_id', 'ad_id', 'triggered_at', 'stats_snapshot', 'before_budget', 'after_budget', 'before_cpm', 'after_cpm', 'actions_applied', 'message'];
    protected $casts = ['triggered_at' => 'datetime', 'stats_snapshot' => 'array', 'actions_applied' => 'array'];

    public function rule(): BelongsTo
    {
        return $this->belongsTo(Rule::class);
    }

    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }
}
