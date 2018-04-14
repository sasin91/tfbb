<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExercisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->increments('id');
            $table->string('provider')->nullable()->index();
            $table->string('provider_id')->nullable();
            
            $table->string('name')->unique();
            $table->string('slug');

            $table->string('category')->default('Physical');
            $table->string('description')->nullable();
            $table->json('muscles')->nullable();
            $table->json('equipment')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('exercises');
    }
}
