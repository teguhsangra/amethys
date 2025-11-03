<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('price_type',['yearly','monthly','daily','hourly','single']);
            $table->double('price',20,2)->default(0);
            $table->integer('total_term')->default(1);
            $table->text('desc')->nullable();
            $table->enum('main_status',['N','Y']);
            $table->enum('quantity_status',['N','Y']);
            $table->enum('tax_status',['no_tax','exclude','include']);
            $table->enum('has_service_charge',['N','Y']);
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
}
