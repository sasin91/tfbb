<?php

use App\Exercise;
use App\Workout;
use App\WorkoutOfTheMonth;
use Illuminate\Database\Seeder;

class WorkoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Workout::class)->times(10)->create()->each(function ($workout) {
        	$workout->addMedia(storage_path('Soldier_Yoga.jpg'))->preservingOriginal()->toMediaCollection('photos');
        	$workout->addMedia(storage_path('Soldier_Yoga.ogv'))->preservingOriginal()->toMediaCollection('videos');
        	$workout->addMedia(storage_path('dummy.pdf'))->preservingOriginal()->toMediaCollection('documents');

        	$workout->exercises()->saveMany(
        		factory(Exercise::class)->times(rand(3,4))->create()
        	);
        });

        WorkoutOfTheMonth::select(Workout::all()->random());
    }
}
