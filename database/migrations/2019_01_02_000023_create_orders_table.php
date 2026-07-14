<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->string('code')->unique();
            $table->date('order_date');
            $table->double('total_price',20,2)->default(0);
            $table->double('total_service_charge',20,2)->default(0);
            $table->double('total_tax_price',20,2)->default(0);
            $table->double('round_price', 20, 2)->default(0);
            $table->double('total_paid',20,2)->default(0);
            $table->enum('usable_discount',['not_use','percentage','price']);
            $table->double('discount_percentage',3,2)->default(0);
            $table->double('discount_price',20,2)->default(0);
            $table->text('remarks')->nullable();
            $table->enum('tax_status',['no_tax','exclude','include']);
            $table->enum('service_charge_status', ['N', 'Y']);
            $table->enum('payment_status',['NP','HP','PA']); // CA = Cancel, NP = Not Paid, HP =Half Paid, PA = Paid
            $table->enum('include_into_main_agreement',['N','Y']); // N = No, Y = Paid
            $table->string('discard_or_cancel_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('draft_by')->nullable();
            $table->string('posting_by')->nullable();
            $table->string('discard_by')->nullable();
            $table->string('complete_by')->nullable();
            $table->string('cancel_by')->nullable();
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('contact_id')->references('id')->on('contacts');
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
        Schema::dropIfExists('orders');
    }
}
