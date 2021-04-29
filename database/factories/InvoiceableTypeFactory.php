<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\InvoiceableType;
use Faker\Generator as Faker;

$factory->define(InvoiceableType::class, function (Faker $faker) {
    return [
        'name' => $faker->word()
    ];
});
