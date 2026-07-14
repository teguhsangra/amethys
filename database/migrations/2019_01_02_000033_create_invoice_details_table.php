<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('booking_detail_id')->nullable();
            $table->unsignedBigInteger('order_detail_id')->nullable();
            $table->unsignedBigInteger('booking_cancellation_id')->nullable();
            $table->string('name')->nullable();
            $table->double('detail_price',20,2)->default(0);
            $table->double('detail_service_charge',20,2)->default(0);
            $table->double('detail_tax_price',20,2)->default(0);
            $table->timestamps();
            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->foreign('booking_detail_id')->references('id')->on('booking_details');
            $table->foreign('order_detail_id')->references('id')->on('order_details');
            $table->foreign('booking_cancellation_id')->references('id')->on('booking_cancellations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_details');
    }
}
