<?php

use Faker\Generator as Faker;

$factory->define(App\Diet::class, function (Faker $faker) {
    return [
		'goal' => $faker->randomElement(['Fat loss', 'Muscle building', 'Contest prep']),
		'style' => $faker->randomElement(['High carb', 'Low carb', 'High protein', 'Low protein', 'Ketogenic', 'Paleo']),
		'title' => $faker->bs,
		'summary' => $faker->paragraph(1), 
		'body' => $faker->realText(300),
		'view' => 'diets.generic'
    ];
});
