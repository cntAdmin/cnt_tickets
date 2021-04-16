<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Attachment;
use Faker\Generator as Faker;

$factory->define(Attachment::class, function (Faker $faker) {
    $attachment_name = $faker->image(storage_path() . '/app/public/media/' . now()->year . '/' . str_pad(now()->month, 2, '0', STR_PAD_LEFT)
        , '500', '500', 'dogs', false, true);
    return [
        'name' => $attachment_name,
        'path' => 'media/' . now()->year . '/' . str_pad(now()->month, 2, '0', STR_PAD_LEFT) . '/' . $attachment_name,
    ];
});
