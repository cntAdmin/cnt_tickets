<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\OriginType;
use Faker\Generator as Faker;

$factory->define(OriginType::class, function (Faker $faker) {
    return [
        'name' => $faker->word()
    ];
});
