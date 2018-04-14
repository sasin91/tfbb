<?php

use Faker\Generator as Faker;

$factory->define(App\Thread::class, function (Faker $faker) {
    return [
     	'creator_id' => factory(App\User::class)->lazy(),
     	'board_id' => factory(App\Board::class)->lazy(),
     	'title' => $faker->bs,
     	'body' => $faker->paragraph(1)
    ];
});

$factory->state(App\Thread::class, 'locked', function (Faker $faker) {
	return ['locked_at' => $faker->dateTimeThisYear()];
});