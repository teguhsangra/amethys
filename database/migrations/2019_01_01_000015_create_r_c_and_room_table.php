<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRCAndRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('r_c_and_room', function (Blueprint $table) {
            $table->unsignedBigInteger('room_category_id')->index();
            $table->unsignedBigInteger('room_id')->index();
            $table->timestamps();
            $table->primary(['room_category_id', 'room_id']);
            $table->foreign('room_category_id')->references('id')->on('room_categories')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('r_c_and_room');
    }
}
