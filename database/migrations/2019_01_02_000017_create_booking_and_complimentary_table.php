<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingAndComplimentaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_and_complimentary', function (Blueprint $table) {
            $table->unsignedBigInteger('booking_id')->index();;
            $table->unsignedBigInteger('complimentary_id')->index();;
            $table->integer('total_complimentary')->default(1);
            $table->timestamps();
            $table->primary(['booking_id', 'complimentary_id']);
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('complimentary_id')->references('id')->on('complimentarys')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_and_complimentary');
    }
}
