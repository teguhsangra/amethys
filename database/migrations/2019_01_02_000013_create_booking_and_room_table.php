<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingAndRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_and_room', function (Blueprint $table) {
            $table->unsignedBigInteger('booking_id')->index();
            $table->unsignedBigInteger('room_id')->index();
            $table->unsignedBigInteger('complimentary_id')->nullable();
            $table->double('detail_price',20,2)->default(0);
            $table->double('other_price',20,2)->default(0);
            $table->integer('detail_use_complimentary')->default(0); // used for any complimentary
            $table->timestamps();
            $table->primary(['booking_id', 'room_id']);
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('complimentary_id')->references('id')->on('complimentarys');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_and_room');
    }
}
