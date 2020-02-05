<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Message;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {
    return [
        'From' => $faker->name,
        'To' => $faker->name,
        'From_user_id' => $faker->randomDigit,
        'To_user_id' => $faker->randomDigit,
        'Message' => $faker->text,
        'Read' => false
    ];
});
