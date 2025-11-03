<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceAndDepositTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_and_deposit', function (Blueprint $table) {
            $table->unsignedBigInteger('invoice_id')->index();
            $table->unsignedBigInteger('deposit_id')->index();
            $table->timestamps();
            $table->primary(['invoice_id', 'deposit_id']);
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('invoice_and_deposit');
    }
}
