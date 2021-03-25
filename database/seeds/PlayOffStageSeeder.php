<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlayOffStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stages = [
            ['name' => \App\Models\PlayOffStage::QUARTERFINAL],
            ['name' => \App\Models\PlayOffStage::SEMIFINAL],
            ['name' => \App\Models\PlayOffStage::THIRDPLACE],
            ['name' => \App\Models\PlayOffStage::FINAL],
        ];

        foreach ($stages as $stage) {
            $_stage = \App\Models\PlayOffStage::where('name', $stage['name'])->first();

            if (!$_stage) {
                \App\Models\PlayOffStage::create($stage);
            }
        }
    }
}
