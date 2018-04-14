<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hashid')->nullable();
            $table->timestamp('locked_at')->nullable();
            $table->string('locked_because')->nullable();
            $table->unsignedInteger('creator_id')->index();
            $table->foreign('creator_id')->references('id')->on('users');
            $table->unsignedInteger('board_id')->index();
            $table->foreign('board_id')->references('id')->on('boards');
            $table->string('title');
            $table->string('body');
            $table->string('summary')->nullable();
            $table->softDeletes();
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
        Schema::drop('threads');
    }
}
