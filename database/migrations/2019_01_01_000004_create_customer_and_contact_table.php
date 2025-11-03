<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerAndContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_and_contact', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('contact_id')->index();
            $table->enum('default_status',['N','Y']);
            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->timestamps();
            $table->primary(['customer_id', 'contact_id']);
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_and_contact');
    }
}
