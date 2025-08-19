<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RuleLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MetricsController extends Controller
{
    public function changes(Request $r)
    {
        $r->validate(['ad_id' => 'required|integer|exists:ads,id']);
        $q = RuleLog::query()->where('ad_id', (int)$r->input('ad_id'));
        if ($r->filled('from')) $q->where('triggered_at', '>=', Carbon::parse($r->input('from')));
        if ($r->filled('to')) $q->where('triggered_at', '<=', Carbon::parse($r->input('to')));
        $logs = $q->orderBy('triggered_at')->get(['triggered_at', 'after_budget', 'after_cpm']);
        return $logs->map(fn($x) => ['t' => $x->triggered_at->toIso8601String(), 'budget' => (float)$x->after_budget, 'cpm' => (float)$x->after_cpm]);
    }
}
