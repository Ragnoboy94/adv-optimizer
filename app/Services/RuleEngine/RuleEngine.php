<?php

namespace App\Services\RuleEngine;

use App\Models\Ad;
use App\Models\AdStat;
use App\Models\Rule;
use App\Models\RuleLog;
use Carbon\Carbon;

final class RuleEngine
{
    public function __construct(
        private ConditionEvaluator $evaluator = new ConditionEvaluator(),
        private ActionApplier $applier = new ActionApplier(),
    ) {}

    public function run(): int
    {
        $now = Carbon::now();
        $rules = Rule::query()->where('is_active', true)->get();
        $triggered = 0;
        foreach ($rules as $rule) {
            $adsQ = $rule->scope_ad_id ? Ad::query()->whereKey($rule->scope_ad_id) : Ad::query();
            foreach ($adsQ->cursor() as $ad) {
                $minutes = max(1, (int)$rule->evaluation_window_minutes);
                $from = $now->copy()->subMinutes($minutes);
                $agg = AdStat::query()
                    ->where('ad_id', $ad->id)
                    ->whereBetween('ts', [$from, $now])
                    ->selectRaw('SUM(views) as views, SUM(clicks) as clicks, SUM(spent) as spent')
                    ->first();
                $views = (int)($agg->views ?? 0);
                $spent = (float)($agg->spent ?? 0);
                $clicks = (int)($agg->clicks ?? 0);
                $ctx = [
                    'budget'=>(float)$ad->budget,
                    'cpm'=>(float)$ad->cpm,
                    'views'=>$views,
                    'spent'=>$spent,
                    'clicks'=>$clicks,
                ];
                if ($this->evaluator->evaluate($rule->condition_tree, $ctx)) {
                    [$before,$after] = $this->applier->apply($ad, $rule->actions);
                    RuleLog::create([
                        'rule_id'=>$rule->id,
                        'ad_id'=>$ad->id,
                        'triggered_at'=>$now,
                        'stats_snapshot'=>$ctx,
                        'before_budget'=>$before['budget'],
                        'after_budget'=>$after['budget'],
                        'before_cpm'=>$before['cpm'],
                        'after_cpm'=>$after['cpm'],
                        'actions_applied'=>$rule->actions,
                        'message'=>sprintf('Rule #%d "%s": budget %.2f -> %.2f; cpm %.4f -> %.4f', $rule->id, $rule->name, $before['budget'], $after['budget'], $before['cpm'], $after['cpm']),
                    ]);
                    $triggered++;
                }
            }
        }
        return $triggered;
    }
}
