<?php

use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $divisions = [
            ['name' => 'Division A'],
            ['name' => 'Division B'],
        ];

        foreach ($divisions as $division) {
            $_division = \App\Models\Division::where('name', $division['name'])->first();

            if (!$_division) {
                \App\Models\Division::create($division);
            }
        }
    }
}
