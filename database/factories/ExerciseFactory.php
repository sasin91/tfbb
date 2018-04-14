<?php

use Faker\Generator as Faker;

$factory->define(App\Exercise::class, function (Faker $faker) {
    return [
    	'provider' => null,
    	'provider_id' => null,
    	'name' => $faker->bs,
    	'description' => $faker->paragraph(1),
    	'muscles' => $faker->randomElement(['Biceps', 'Triceps', 'Trapezius', 'Latissimus dorsi', 'Pectoralis major']),
    	'equipment' => $faker->randomElement(['Barbell', 'Dumbbell', 'Kettlebell'])
    ];
});
