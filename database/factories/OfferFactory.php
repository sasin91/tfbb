<?php

use Faker\Generator as Faker;

$factory->define(App\Offer::class, function (Faker $faker) {
    return [
    	'name' => $faker->bs,
    	'tagline' => $faker->bs,
    	'discount' => $faker->numberBetween(5, 20), 
    	'body' => $faker->text(1200),
    	'poster_url' => $faker->imageUrl(),
    	'banner_url' => $faker->imageUrl(),
    	'offsite_link' => $faker->url(),
        'view' => 'offers.generic'
    ];
});

$factory->state(App\Offer::class, 'with testimonials', [])->afterCreatingState(App\Offer::class, 'with testimonials', function ($offer, $faker) {
	$offer->testimonials()->syncWithoutDetaching(factory(App\Testimonial::class)->times(3)->create());
});
