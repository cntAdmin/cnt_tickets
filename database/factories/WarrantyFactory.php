<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Warranty;
use Faker\Generator as Faker;

$factory->define(Warranty::class, function (Faker $faker) {
    return [
        'name' => $faker->word()
    ];
});
