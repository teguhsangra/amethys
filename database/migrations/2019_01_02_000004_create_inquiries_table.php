<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('prospect_id')->nullable();
            $table->unsignedBigInteger('referral_id')->nullable();
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->unsignedBigInteger('room_category_id')->nullable();
            $table->unsignedBigInteger('room_type_id')->nullable();
            $table->unsignedBigInteger('room_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->unsignedBigInteger('inquiry_id')->nullable();
            $table->string('code')->unique();
            $table->enum('type', ['package', 'product', 'room']);
            $table->enum('price_type', ['yearly', 'monthly', 'daily', 'hourly']);
            $table->enum('room_price_type', ['default', 'bare', 'fitted', 'furnished']);
            $table->enum('customer_status', ['N', 'E']); // N = New, E = Exist
            $table->enum('tax_status', ['no_tax', 'exclude', 'include']);
            $table->enum('start_date_counted', ['N', 'Y']); // Will be using while count total inquiry
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->date('start_date'); // Commencement Date
            $table->date('end_date');
            $table->integer('length_of_term')->default(1);
            $table->integer('length_of_term_after_office')->default(0);
            $table->integer('term_notice_period')->nullable();
            $table->integer('term_of_payment')->nullable();
            $table->integer('free_term_booking')->nullable();
            $table->text('remarks')->nullable();
            $table->double('detail_price', 20, 2);
            $table->enum('usable_discount', ['not_use', 'percentage', 'price']);
            $table->double('discount_percentage', 20, 2)->default(0);
            $table->double('discount_price', 20, 2)->default(0);
            $table->double('total_price', 20, 2)->default(0);
            $table->double('total_service_charge', 20, 2)->default(0);
            $table->double('total_tax_price', 20, 2)->default(0);
            $table->enum('ac_usable_discount', ['not_use', 'percentage', 'price']);
            $table->double('ac_discount_percentage', 20, 2)->default(0);
            $table->double('ac_discount_price', 20, 2)->default(0);
            $table->double('total_additional_charge', 20, 2)->default(0);
            $table->double('total_service_charge_additional_charge', 20, 2)->default(0);
            $table->double('total_tax_additional_charge', 20, 2)->default(0);
            $table->double('security_deposit', 20, 2)->default(0);
            $table->double('stamp_duty', 20, 2)->default(0);
            $table->double('round_price', 20, 2)->default(0);
            $table->integer('quantity')->default(1);
            $table->integer('total_print')->default(0);
            $table->integer('total_send_email')->default(0);
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
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('prospect_id')->references('id')->on('prospects');
            $table->foreign('referral_id')->references('id')->on('referrals');
            $table->foreign('agent_id')->references('id')->on('agents');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->foreign('room_category_id')->references('id')->on('room_categories');
            $table->foreign('room_type_id')->references('id')->on('room_types');
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('package_id')->references('id')->on('packages');
            $table->foreign('inquiry_id')->references('id')->on('inquiries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inquiries');
    }
}
