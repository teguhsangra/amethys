<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVCAndVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('v_c_and_vendor', function (Blueprint $table) {
            $table->unsignedBigInteger('vendor_category_id')->index();
            $table->unsignedBigInteger('vendor_id')->index();
            $table->timestamps();
            $table->primary(['vendor_category_id', 'vendor_id']);
            $table->foreign('vendor_category_id')->references('id')->on('vendor_categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('v_c_and_vendor');
    }
}
