<?php

namespace App\Jobs;

use App\Services\RuleEngine\RuleEngine;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class EvaluateRulesJob implements ShouldQueue
{
    use Dispatchable, Queueable;
    public function handle(RuleEngine $engine): void { $engine->run(); }
}
