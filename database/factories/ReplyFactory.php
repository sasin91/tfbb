<?php

use Faker\Generator as Faker;

$factory->define(App\Reply::class, function (Faker $faker) {
    return [
        'creator_id' => factory(App\User::class)->lazy(),
        'thread_id' => factory(App\Thread::class)->lazy(),
        'title' => $faker->bs,
        'body' => $faker->paragraph(1)
    ];
});
