<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingDedicatedPhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_dedicated_phones', function (Blueprint $table) {
            $table->unsignedBigInteger('booking_id')->index();
            $table->unsignedBigInteger('dedicated_phone_id')->index();
            $table->timestamps();
            $table->primary(['booking_id', 'dedicated_phone_id']);
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('dedicated_phone_id')->references('id')->on('dedicated_phones')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_dedicated_phones');
    }
}
