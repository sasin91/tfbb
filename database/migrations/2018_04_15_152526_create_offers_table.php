<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('tagline')->nullable();
            $table->string('banner_url')->nullable();
            $table->string('poster_url')->nullable();
            $table->decimal('discount')->default(0);
            $table->string('offsite_link')->nullable();
            $table->text('body')->nullable();

            $table->string('view')->default('offers.generic');
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
        Schema::drop('offers');
    }
}
