<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Priority;
use Faker\Generator as Faker;

$factory->define(Priority::class, function (Faker $faker) {
    return [
        'name' => $faker->word()
    ];
});
