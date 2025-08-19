<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RuleLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $r)
    {
        $q = RuleLog::query()->with(['rule:id,name', 'ad:id,title']);
        if ($r->filled('ad_id')) $q->where('ad_id', (int)$r->input('ad_id'));
        if ($r->filled('from')) $q->where('triggered_at', '>=', Carbon::parse($r->input('from')));
        if ($r->filled('to')) $q->where('triggered_at', '<=', Carbon::parse($r->input('to')));
        return $q->orderBy('triggered_at', 'desc')->paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
