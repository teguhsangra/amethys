<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('vendor_id');
            $table->string('code')->unique();
            $table->enum('payment_status',['NP','PA']); // NP = Not Paid, PA = Paid
            $table->double('total_price',20,2)->default(0);
            $table->double('total_tax_price',20,2)->default(0);
            $table->string('notes')->nullable();
            $table->string('payment_receipt')->nullable();
            $table->string('discard_or_cancel_reason')->nullable();
            $table->timestamps();
            $table->string('draft_by')->nullable();
            $table->string('posting_by')->nullable();
            $table->string('discard_by')->nullable();
            $table->string('complete_by')->nullable();
            $table->string('cancel_by')->nullable();
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->foreign('vendor_id')->references('id')->on('vendors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_orders');
    }
}
