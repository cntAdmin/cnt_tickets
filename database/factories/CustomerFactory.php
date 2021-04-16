<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Customer;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'cif' => $faker->vat,
        'name' => $faker->company,
        'alias' => $faker->company,
        'email' => $faker->unique()->email,
        'address' => $faker->streetAddress,
        'town' => $faker->city,
        'postcode' => $faker->postcode,
        'phone' => '34' . $faker->randomNumber(9, true),
        'fax' => '34' . $faker->randomNumber(9, true),
        'is_active' => $faker->boolean(),
    ];
});
