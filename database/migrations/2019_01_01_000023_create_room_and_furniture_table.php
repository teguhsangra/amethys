<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomAndFurnitureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_and_furniture', function (Blueprint $table) {
            $table->unsignedBigInteger('room_id')->index();
            $table->unsignedBigInteger('furniture_id')->index();
            $table->integer('quantity')->default(0);
            $table->timestamps();
            $table->primary(['room_id', 'furniture_id']);
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('room_and_furniture');
    }
}
