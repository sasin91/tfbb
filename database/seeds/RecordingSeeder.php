<?php

use App\Recording;
use Illuminate\Database\Seeder;

class RecordingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Recording::class)->times(15)->create()->each(function ($recording) {
            $recording->addMedia(storage_path('Soldier_Yoga.ogv'))->preservingOriginal()->toMediaCollection('videos');
        });
    }
}
