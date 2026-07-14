<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFurniturePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('furniture_photos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('furniture_id');
            $table->string('photo');
            $table->enum('default',['N','Y']); // N: No, Y:Yes
            $table->timestamps();
            $table->foreign('furniture_id')->references('id')->on('furniture');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('furniture_photos');
    }
}
