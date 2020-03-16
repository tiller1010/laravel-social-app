<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Message;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {
	$from = App\User::inRandomOrder()->first();
	$to = App\User::inRandomOrder()->first();
	if($from == $to){
		$to = App\User::inRandomOrder()->first();
	}
    return [
        'From' => $from->name,
        'To' => $to->name,
        'From_user_id' => $from->id,
        'To_user_id' => $to->id,
        'Message' => $faker->text,
        'Read' => false
    ];
});
