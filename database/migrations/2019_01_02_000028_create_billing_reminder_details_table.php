<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillingReminderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_reminder_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('billing_reminder_id');
            $table->unsignedBigInteger('booking_detail_id')->nullable();
            $table->unsignedBigInteger('order_detail_id')->nullable();
            $table->unsignedBigInteger('booking_cancellation_id')->nullable();
            $table->timestamps();
            $table->foreign('billing_reminder_id')->references('id')->on('billing_reminders');
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
        Schema::dropIfExists('billing_reminder_details');
    }
}
