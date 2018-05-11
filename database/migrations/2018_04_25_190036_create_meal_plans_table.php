<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMealPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meal_plans', function (Blueprint $table) {
            $table->unsignedInteger('meal_id')->index();
            $table->foreign('meal_id')->references('id')->on('meals')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('food_id')->index();
            $table->foreign('food_id')->references('id')->on('foods')->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['meal_id', 'food_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('meal_plans');
    }
}
