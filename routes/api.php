<?php

use App\Http\Controllers\Api\AdController;
use App\Http\Controllers\Api\LogController;
use App\Http\Controllers\Api\MetricsController;
use App\Http\Controllers\Api\RuleController;
use App\Services\RuleEngine\RuleEngine;
use Illuminate\Support\Facades\Route;

Route::get('/ads', [AdController::class, 'index']);
Route::get('/rules', [RuleController::class, 'index']);
Route::post('/rules', [RuleController::class, 'store']);
Route::get('/rules/{rule}', [RuleController::class, 'show']);
Route::put('/rules/{rule}', [RuleController::class, 'update']);
Route::post('/rules/{rule}/toggle', [RuleController::class, 'toggle']);
Route::delete('/rules/{rule}', [RuleController::class, 'destroy']);
Route::get('/logs', [LogController::class, 'index']);
Route::get('/metrics/changes', [MetricsController::class, 'changes']);
Route::get('/run-once', function (RuleEngine $engine) {
    return ['triggered' => $engine->run()];
});
