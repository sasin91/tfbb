<?php

use Faker\Generator as Faker;

$factory->define(App\Workout::class, function (Faker $faker) {
    return [
    	'title' => $faker->bs, 
    	'level' => $faker->randomElement(['Beginner', 'Intermediate', 'Advanced', 'Elite']), 
    	'type' => $faker->randomElement(['Bodybuilding', 'Powerlifting', 'Weight lifting', 'Hybrid']),
    	'weeks' => $faker->randomNumber(1),
    	'summary' => $faker->paragraph(1), 
    	'body' => $faker->paragraph(1)
    ];
});
