<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('prospect_id')->nullable();
            $table->unsignedBigInteger('previous_id')->nullable();
            $table->unsignedBigInteger('marketing_material_id')->nullable();
            $table->unsignedBigInteger('inquiry_id')->nullable();
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('contact_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('code')->unique();
            $table->enum('source_status', ['prospect', 'previous_activity', 'existing_customer']);
            $table->enum('type', ['visit', 'call', 'offering', 'dealing']);
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->string('discard_or_cancel_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('draft_by')->nullable();
            $table->string('posting_by')->nullable();
            $table->string('discard_by')->nullable();
            $table->string('complete_by')->nullable();
            $table->string('cancel_by')->nullable();
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->foreign('prospect_id')->references('id')->on('prospects');
            $table->foreign('previous_id')->references('id')->on('sales_activities');
            $table->foreign('marketing_material_id')->references('id')->on('marketing_materials');
            $table->foreign('inquiry_id')->references('id')->on('inquiries');
            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->foreign('employee_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_activities');
    }
}
