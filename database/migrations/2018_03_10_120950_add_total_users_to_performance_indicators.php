<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalUsersToPerformanceIndicators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('performance_indicators', function (Blueprint $table) {
            $table->unsignedInteger('total_users')->nullable();
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
            $table->dropColumn('total_users');
        });
    }
}
