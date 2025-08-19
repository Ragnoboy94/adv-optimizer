<?php

namespace Database\Seeders;

use App\Models\Ad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=1;$i<=8;$i++) {
            Ad::create([
                'title'=>"Ad #$i",
                'budget'=>mt_rand(1000,5000)/100,
                'cpm'=>mt_rand(100,900)/100,
            ]);
        }
    }
}
