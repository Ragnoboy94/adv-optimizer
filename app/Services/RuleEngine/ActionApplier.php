<?php

namespace App\Services\RuleEngine;

use App\Models\Ad;

final class ActionApplier
{
    public function apply(Ad $ad, array $actions): array
    {
        $before = ['budget'=>(float)$ad->budget,'cpm'=>(float)$ad->cpm];
        $after = $before;
        foreach ($actions as $a) {
            $target = strtolower((string)($a['target'] ?? ''));
            $op = strtolower((string)($a['op'] ?? ''));
            $val = (float)($a['value'] ?? 0);
            if (!in_array($target,['budget','cpm'],true)) continue;
            $current = $after[$target];
            $current = match ($op) {
                'increase' => $current + $val,
                'decrease' => $current - $val,
                'set' => $val,
                default => $current
            };
            if ($current < 0) $current = 0;
            $after[$target] = $current;
        }
        $ad->budget = $after['budget'];
        $ad->cpm = $after['cpm'];
        $ad->save();
        return [$before,$after];
    }
}
