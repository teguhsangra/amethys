<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplimentarysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complimentarys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('room_category_id')->nullable();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('used_for_url');
            $table->enum('price_type', ['yearly', 'monthly', 'daily', 'hourly']);
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->foreign('room_category_id')->references('id')->on('room_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complimentarys');
    }
}
