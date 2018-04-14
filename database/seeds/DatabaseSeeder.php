<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(TeamSeeder::class);
        $this->call(WorkoutSeeder::class);
        $this->call(RecordingSeeder::class);
        $this->call(VibrantCommunitySeeder::class);
    }
}
