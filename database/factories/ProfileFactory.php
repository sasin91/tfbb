<?php

use Faker\Generator as Faker;

$factory->define(App\Profile::class, function (Faker $faker) {
    return [
        'creator_id' => factory(App\User::class)->lazy()
    ];
});

$factory->state(App\Profile::class, 'filled', function (Faker $faker) {
	return [
    	'story' => $faker->paragraph(1),
    	'goals' => $faker->paragraph(1),
	];
});


$factory->state(App\Profile::class, 'published', function (Faker $faker) {
	return ['published_at' => $faker->dateTimeThisYear()];
});

$factory->state(App\Profile::class, 'locked', function (Faker $faker) {
	return ['locked_at' => $faker->dateTimeThisYear()];
});