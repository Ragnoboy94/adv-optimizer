<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule as V;

class RuleController extends Controller
{
    public function index()
    {
        return Rule::query()->orderBy('id', 'desc')->get();
    }

    public function show(Rule $rule)
    {
        return $rule;
    }

    public function store(Request $r)
    {
        $data = $this->validateData($r);
        return Rule::create($data);
    }

    public function update(Request $r, Rule $rule)
    {
        $data = $this->validateData($r);
        $rule->update($data);
        return $rule->fresh();
    }

    public function toggle(Rule $rule)
    {
        $rule->is_active = !$rule->is_active;
        $rule->save();
        return ['is_active' => $rule->is_active];
    }

    public function destroy(Rule $rule)
    {
        $rule->delete();
        return response()->noContent();
    }

    private function validateData(Request $r): array
    {
        return $r->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
            'scope_ad_id' => ['nullable', 'integer', 'exists:ads,id'],
            'evaluation_window_minutes' => ['required', 'integer', 'min:1', 'max:1440'],
            'condition_tree' => ['required', 'array'],
            'actions' => ['required', 'array', 'min:1'],
            'actions.*.target' => ['required', V::in(['budget', 'cpm'])],
            'actions.*.op' => ['required', V::in(['increase', 'decrease', 'set'])],
            'actions.*.value' => ['required', 'numeric', 'min:0'],
        ]);
    }
}
