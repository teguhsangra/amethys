<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInquiryAndRoomTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiry_and_room_type', function (Blueprint $table) {
            $table->unsignedBigInteger('inquiry_id')->index();
            $table->unsignedBigInteger('room_type_id')->index();
            $table->double('detail_price',20,2)->default(0);
            $table->timestamps();
            $table->primary(['inquiry_id', 'room_type_id']);
            $table->foreign('inquiry_id')->references('id')->on('inquiries')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('room_type_id')->references('id')->on('room_types')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inquiry_and_room_type');
    }
}
