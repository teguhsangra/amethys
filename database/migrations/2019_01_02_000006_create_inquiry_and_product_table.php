<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInquiryAndProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiry_and_product', function (Blueprint $table) { // For additional service
            $table->unsignedBigInteger('inquiry_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->double('detail_price',20,2)->default(0);
            $table->double('quantity',20,2)->default(0);
            $table->date('start_date')->nullable(); // For Detail Transaction
            $table->date('end_date')->nullable(); // For Detail Transaction
            $table->time('start_time')->nullable(); // For Detail Transaction
            $table->time('end_time')->nullable(); // For Detail Transaction
            $table->integer('length_of_term')->default(1);
            $table->timestamps();
            $table->primary(['inquiry_id', 'product_id']);
            $table->foreign('inquiry_id')->references('id')->on('inquiries')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inquiry_and_product');
    }
}
