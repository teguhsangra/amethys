<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingCancellationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_cancellations', function (Blueprint $table) { // cancellation akan menggunakan POS
            $table->bigIncrements('id');
            $table->unsignedBigInteger('booking_id');
            $table->double('total_price',20,2);
            $table->double('total_service_charge',20,2)->default(0);
            $table->double('total_tax_price',20,2);
            $table->double('round_price', 20, 2)->default(0);
            $table->double('total_paid',20,2)->default(0);
            $table->enum('payment_status',['NP','HP','PA']); // CA = Cancel, NP = Not Paid, HP =Half Paid, PA = Paid
            $table->timestamps();
            $table->foreign('booking_id')->references('id')->on('bookings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_cancellations');
    }
}
