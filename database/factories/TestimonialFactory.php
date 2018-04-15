<?php

use Faker\Generator as Faker;

$factory->define(App\Testimonial::class, function (Faker $faker) {
    return [
	 	'reviewer' => $faker->name, 
	 	'reviewer_photo_url' => null,
	 	'title' => $faker->bs, 
	 	'body' => $faker->realText()
    ];
});

$factory->state(App\Testimonial::class, 'with random photo', function (Faker $faker) {
	return ['reviewer_photo_url' => $faker->imageUrl()];
});