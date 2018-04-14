<?php

use Faker\Generator as Faker;

$factory->define(App\Comment::class, function (Faker $faker) {
    return [
    	'creator_id' => factory(App\User::class)->lazy(),
    	'title' => $faker->bs,
    	'body' => $faker->paragraph(1)
    ];
});
