<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->unsignedBigInteger('room_id')->nullable();
            $table->unsignedBigInteger('complimentary_id')->nullable();
            $table->enum('payment_status',['NP','PA']); // NP = Not Paid, PA = Paid
            $table->integer('month');
            $table->integer('year');
            $table->integer('detail_sequence');
            $table->double('detail_price',20,2)->default(0);
            $table->double('detail_service_charge',20,2)->default(0);
            $table->double('detail_tax_price',20,2)->default(0);
            $table->enum('usable_discount',['not_use','percentage','price']);
            $table->double('detail_discount_percentage',20,2)->default(0);
            $table->double('detail_discount_price',20,2)->default(0);
            $table->string('desc')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('length_of_detail')->default(1);
            $table->integer('quantity')->default(1);
            $table->integer('detail_use_complimentary')->default(0); // used for any complimentary
            $table->timestamps();
            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('package_id')->references('id')->on('packages');
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->foreign('complimentary_id')->references('id')->on('complimentarys');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_details');
    }
}
