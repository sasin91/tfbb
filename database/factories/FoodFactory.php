<?php

use Faker\Generator as Faker;

$factory->define(App\Food::class, function (Faker $faker) {
    return [
		'name' => $faker->bs,
		'energy' => $faker->randomNumber(3),
		'protein' => $faker->randomNumber(3),
		'fat' => $faker->randomNumber(3),
		'carbohydrate' => $faker->randomNumber(3) 
    ];
});
