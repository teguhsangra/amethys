<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceListDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_list_details', function (Blueprint $table) {
            $table->unsignedBigInteger('invoice_list_id')->index();
            $table->unsignedBigInteger('invoice_id')->index();
            $table->primary(['invoice_list_id', 'invoice_id']);
            $table->timestamps();
            $table->foreign('invoice_list_id')->references('id')->on('invoice_lists')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_list_details');
    }
}
