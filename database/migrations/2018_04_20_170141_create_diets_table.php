<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDietsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('goal')->index();
            $table->string('style')->index();
            $table->string('title');
            $table->string('slug');
            $table->string('summary');
            $table->text('body');
            $table->string('view')->index();
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
        Schema::drop('diets');
    }
}
