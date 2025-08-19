<?php

namespace App\Services\RuleEngine;

final class ConditionEvaluator
{
    public function evaluate(array $tree, array $ctx): bool
    {
        if (($tree['type'] ?? null) === 'predicate') {
            $metric = strtolower((string)($tree['metric'] ?? ''));
            $op = (string)($tree['op'] ?? '==');
            $value = (float)($tree['value'] ?? 0);
            if (!array_key_exists($metric, $ctx)) return false;
            $left = (float)$ctx[$metric];
            return match ($op) {
                '>' => $left > $value,
                '>=' => $left >= $value,
                '<' => $left < $value,
                '<=' => $left <= $value,
                '==' => abs($left - $value) < 1e-9,
                '!=' => abs($left - $value) >= 1e-9,
                default => false
            };
        }
        if (($tree['type'] ?? null) === 'group') {
            $op = strtoupper($tree['op'] ?? 'AND');
            $children = $tree['children'] ?? [];
            if ($op === 'AND') {
                foreach ($children as $ch) if (!$this->evaluate($ch, $ctx)) return false;
                return true;
            }
            if ($op === 'OR') {
                foreach ($children as $ch) if ($this->evaluate($ch, $ctx)) return true;
                return false;
            }
        }
        return false;
    }
}
