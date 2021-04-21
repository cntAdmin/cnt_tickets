<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\TicketTimeslot;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Date;

$factory->define(TicketTimeslot::class, function (Faker $faker) {
    $start_time = Carbon::now()->subDays(30);
    return [
        'start_date_time' => $start_time->copy()->toDateTimeString(),
        'end_date_time' => $start_time->copy()->addHours(rand(1,8))->toDateTimeString()
    ];
});
