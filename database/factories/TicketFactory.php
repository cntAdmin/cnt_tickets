<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Ticket;
use Faker\Generator as Faker;

$factory->define(Ticket::class, function (Faker $faker) {
    return [
        'title' => $faker->words(5, true),
        'description' => $faker->paragraphs(3, true),
        'department_type_id' => rand(1, 3),
        'read_by_admin' => $faker->boolean(),
    ];
});
