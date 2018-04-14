<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewThreadsToPerformanceIndicators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('performance_indicators', function (Blueprint $table) {
            $table->unsignedInteger('new_threads')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('performance_indicators', function (Blueprint $table) {
            $table->dropColumn('new_threads');
        });
    }
}
