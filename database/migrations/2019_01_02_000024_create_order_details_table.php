<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->enum('payment_status',['NP','PA']); // NP = Not Paid, PA = Paid
            $table->date('start_date')->nullable(); // For Detail Transaction
            $table->date('end_date')->nullable(); // For Detail Transaction
            $table->time('start_time')->nullable(); // For Detail Transaction
            $table->time('end_time')->nullable(); // For Detail Transaction
            $table->integer('length_of_term')->default(1);
            $table->integer('quantity')->default(0);
            $table->double('detail_price',20,2)->default(0);
            $table->double('detail_service_charge',20,2)->default(0);
            $table->double('detail_tax_price',20,2)->default(0);
            $table->enum('usable_discount',['not_use','percentage','price']);
            $table->double('detail_discount_percentage',3,2)->default(0);
            $table->double('detail_discount_price',20,2)->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');;
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
