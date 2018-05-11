<?php

use App\Diet;
use Facades\App\Scores\Popularity;
use Illuminate\Database\Seeder;

class DietSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Popularity::forget(Diet::class);

        $diets = factory(Diet::class)->states('with meals')->times(10)->create();

        $diets->shuffle()->take(3)->each(function ($diet) {
        	Popularity::increment($diet, rand(1,20));
        });
    }
}
