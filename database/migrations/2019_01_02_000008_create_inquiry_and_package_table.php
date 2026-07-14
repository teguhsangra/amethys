<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInquiryAndPackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiry_and_package', function (Blueprint $table) {
            $table->unsignedBigInteger('inquiry_id')->index();
            $table->unsignedBigInteger('package_id')->index();
            $table->enum('price_type', ['yearly', 'monthly', 'daily', 'hourly', 'halfday']);
            $table->double('detail_price',20,2)->default(0);
            $table->double('quantity',20,2)->default(0);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->date('start_date')->nullable(); // Commencement Date
            $table->date('end_date')->nullable();
            $table->integer('length_of_term')->default(1);
            $table->timestamps();
            $table->primary(['inquiry_id', 'package_id']);
            $table->foreign('inquiry_id')->references('id')->on('inquiries')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inquiry_and_package');
    }
}
