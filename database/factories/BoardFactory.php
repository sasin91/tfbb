<?php

use Faker\Generator as Faker;

$factory->define(App\Board::class, function (Faker $faker) {
    return [
        'name' => $faker->bs,
        'description' => $faker->paragraph(1)
    ];
});

$factory->state(App\Board::class, 'published', function ($faker) {
	return ['published_at' => $faker->dateTimeThisYear()];
});