<?php

use Faker\Generator as Faker;

$factory->define(App\Diet::class, function (Faker $faker) {
    return [
    	'banner_url' => $faker->imageUrl(),
		'goal' => $faker->randomElement(['Fat loss', 'Muscle building', 'Contest prep']),
		'style' => $faker->randomElement(['High carb', 'Low carb', 'High protein', 'Low protein', 'Ketogenic', 'Paleo']),
		'title' => $faker->bs,
		'summary' => $faker->paragraph(1), 
		'body' => $faker->realText(300),
		'view' => 'diets.generic'
    ];
});

$factory
	->state(App\Diet::class, 'with meals', [])
	->afterCreatingState(App\Diet::class, 'with meals', function ($diet, $faker) {
		$diet->meals()->saveMany(
			factory(App\Meal::class)->states('with foods')->times(rand(3,6))->create()
		);
	});