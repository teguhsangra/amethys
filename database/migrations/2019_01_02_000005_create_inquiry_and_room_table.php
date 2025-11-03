<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInquiryAndRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiry_and_room', function (Blueprint $table) {
            $table->unsignedBigInteger('inquiry_id')->index();
            $table->unsignedBigInteger('room_id')->index();
            $table->double('detail_price',20,2)->default(0);
            $table->timestamps();
            $table->primary(['inquiry_id', 'room_id']);
            $table->foreign('inquiry_id')->references('id')->on('inquiries')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inquiry_and_room');
    }
}
