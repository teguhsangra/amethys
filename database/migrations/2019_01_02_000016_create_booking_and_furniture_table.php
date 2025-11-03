<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingAndFurnitureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_and_furniture', function (Blueprint $table) {
            $table->unsignedBigInteger('booking_id')->index();
            $table->unsignedBigInteger('furniture_id')->index();
            $table->integer('quantity')->default(0);
            $table->timestamps();
            $table->primary(['booking_id', 'furniture_id']);
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('furniture_id')->references('id')->on('furniture')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_and_furniture');
    }
}
