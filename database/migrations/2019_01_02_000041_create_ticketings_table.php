<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticketings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id'); // user yg membuat tiket
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable(); // diisi sesuai dengan id employee yg sedang login
            $table->unsignedBigInteger('room_id')->nullable(); // Pilih salah satu
            $table->unsignedBigInteger('product_id')->nullable(); // Pilih salah satu
            $table->unsignedBigInteger('package_id')->nullable(); // Pilih salah satu
            $table->unsignedBigInteger('booking_id')->nullable(); // Pilih salah satu
            $table->unsignedBigInteger('order_id')->nullable(); // Pilih salah satu
            $table->unsignedBigInteger('ticketing_subject_id')->nullable();
            $table->string('code')->unique();
            $table->enum('is_closed', ['N', 'Y']);
            $table->string('subject')->nullable(); // diisi jika ticket_subject id tidak diisi
            $table->text('remarks');
            $table->timestamps();
            $table->softDeletes();
            $table->dateTime('closed_at')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('package_id')->references('id')->on('packages');
            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('ticketing_subject_id')->references('id')->on('ticketing_subjects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticketings');
    }
}
