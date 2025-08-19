<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\AdStat;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ads = Ad::all();
        $start = Carbon::now()->subDays(7)->startOfDay();
        foreach ($ads as $ad) {
            $t = $start->copy();
            while ($t->lessThan(Carbon::now())) {
                $views = (int)mt_rand(100,1500);
                $clicks = (int)floor($views*(mt_rand(10,50)/1000));
                $spent = $views*(mt_rand(50,250)/1000);
                AdStat::create([
                    'ad_id'=>$ad->id,
                    'ts'=>$t,
                    'views'=>$views,
                    'clicks'=>$clicks,
                    'spent'=>round($spent,2),
                ]);
                $t->addHour();
            }
        }
    }
}
