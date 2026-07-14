<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProformaAndDepositTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proforma_and_deposit', function (Blueprint $table) {
            $table->unsignedBigInteger('proforma_id')->index();
            $table->unsignedBigInteger('deposit_id')->index();
            $table->timestamps();
            $table->primary(['proforma_id', 'deposit_id']);
            $table->foreign('proforma_id')->references('id')->on('proformas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('deposit_id')->references('id')->on('deposits')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proforma_and_deposit');
    }
}
