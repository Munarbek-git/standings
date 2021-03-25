<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\PlayOffStage::class, function (Faker $faker) {
    return [
        "name" => 'Stage '.$faker->unique()->lexify('?')
    ];
});
