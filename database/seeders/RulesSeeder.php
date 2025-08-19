<?php

namespace Database\Seeders;

use App\Models\Rule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rule::create([
            'name'=>'Spent>10 AND Clicks<50 => CPM-1',
            'is_active'=>true,
            'scope_ad_id'=>null,
            'evaluation_window_minutes'=>60,
            'condition_tree'=>[
                'type'=>'group','op'=>'AND','children'=>[
                    ['type'=>'predicate','metric'=>'spent','op'=>'>','value'=>10],
                    ['type'=>'predicate','metric'=>'clicks','op'=>'<','value'=>50],
                ],
            ],
            'actions'=>[
                ['target'=>'cpm','op'=>'decrease','value'=>1],
            ],
        ]);
        Rule::create([
            'name'=>'Views>1000 AND Spent<5 => Budget+1',
            'is_active'=>true,
            'scope_ad_id'=>null,
            'evaluation_window_minutes'=>60,
            'condition_tree'=>[
                'type'=>'group','op'=>'AND','children'=>[
                    ['type'=>'predicate','metric'=>'views','op'=>'>','value'=>1000],
                    ['type'=>'predicate','metric'=>'spent','op'=>'<','value'=>5],
                ],
            ],
            'actions'=>[
                ['target'=>'budget','op'=>'increase','value'=>1],
            ],
        ]);
    }
}
