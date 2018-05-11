<?php

use Faker\Generator as Faker;

$factory->define(App\Meal::class, function (Faker $faker) {
    return [
		'name' => $faker->bs, 
		'type' => $faker->randomElement(['Breakfast', 'Lunch', 'Dinner', 'Snack']),
		'description' => $faker->paragraph(1),
		'photo_url' => $faker->imageUrl()
    ];
});

$factory
	->state(App\Meal::class, 'with foods', [])
	->afterCreatingState(App\Meal::class, 'with foods', function ($diet, $faker) {
		$diet->foods()->saveMany(
			factory(App\Food::class)->times(rand(1,3))->create()
		);
	});
