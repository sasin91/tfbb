<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfferTestimonialPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_testimonial', function (Blueprint $table) {
            $table->integer('offer_id')->unsigned()->index();
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
            $table->integer('testimonial_id')->unsigned()->index();
            $table->foreign('testimonial_id')->references('id')->on('testimonials')->onDelete('cascade');
            $table->primary(['offer_id', 'testimonial_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('offer_testimonial');
    }
}
