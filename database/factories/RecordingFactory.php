<?php

use Faker\Generator as Faker;

$factory->define(App\Recording::class, function (Faker $faker) {
    return [
		'category' => $faker->bs, 
		'title' => $faker->paragraph(1),  
		'body' => $faker->realText(200)
    ];
});

$factory->state(App\Recording::class, 'large body', function (Faker $faker) {
	return ['body' => $faker->realText(65535)];
});
