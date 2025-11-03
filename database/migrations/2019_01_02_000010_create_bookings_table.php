<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('inquiry_id')->nullable();
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('referral_id')->nullable();
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->unsignedBigInteger('room_category_id')->nullable();
            $table->unsignedBigInteger('room_id')->nullable(); // Used for coworking & meeting
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->unsignedBigInteger('complimentary_id')->nullable();
            $table->unsignedBigInteger('deposit_id')->nullable();
            $table->string('code')->unique();
            $table->enum('type', ['package', 'product', 'room']);
            $table->enum('price_type', ['yearly', 'monthly', 'daily', 'hourly', 'halfday']);
            $table->enum('room_price_type', ['default', 'bare', 'fitted', 'furnished']);
            $table->enum('is_main_agreement', ['Y', 'N']); // N = No, Y = Yes
            $table->enum('is_renewal', ['N', 'Y']); // N = No, Y = Yes
            $table->enum('holiday_status', ['N', 'Y']); // N = No, Y = Yes
            $table->enum('customer_status', ['N', 'E']); // N = New, E = Exist
            $table->enum('breakfast_status', ['N', 'Y'])->default('N'); // N = No, Y = Yes
            $table->enum('renewal_status', ['OR', 'RN', 'TM']); // OR = On Reminder, RN = Renewal, TM = Terminated
            $table->enum('tax_status', ['no_tax', 'exclude', 'include']);
            $table->enum('start_date_counted', ['N', 'Y']); // Will be using for started date
            $table->enum('address_status',['location', 'customer']);
            $table->string('dedicated_phone')->nullable();
            $table->string('dedicated_fax')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->date('start_date'); // Commencement Date
            $table->date('end_date');
            $table->date('signed_date')->nullable();
            $table->integer('length_of_term')->default(1);
            $table->integer('length_of_term_after_office')->default(0);
            $table->integer('term_notice_period')->nullable();
            $table->integer('term_of_payment')->nullable();
            $table->integer('free_term_booking')->default(0);
            $table->text('remarks')->nullable();
            $table->double('detail_price', 20, 2)->default(0);
            $table->enum('usable_discount', ['not_use', 'percentage', 'price']);
            $table->double('discount_percentage', 20, 2)->default(0);
            $table->double('discount_price', 20, 2)->default(0);
            $table->double('total_price', 20, 2)->default(0);
            $table->double('total_service_charge',20,2)->default(0);
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
            $table->double('total_paid', 20, 2)->default(0);
            $table->integer('quantity')->default(1);
            $table->integer('total_print')->default(0);
            $table->integer('total_send_email')->default(0);
            $table->integer('total_use_complimentary')->default(0); // used for any complimentary
            $table->enum('payment_status', ['NP', 'HP', 'PA']); // CA = Cancel, NP = Not Paid, HP =Half Paid, PA = Paid
            $table->string('discard_or_cancel_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('draft_by')->nullable();
            $table->string('posting_by')->nullable();
            $table->string('discard_by')->nullable();
            $table->string('complete_by')->nullable();
            $table->string('cancel_by')->nullable();
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('bank_account_id')->references('id')->on('bank_accounts');
            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->foreign('inquiry_id')->references('id')->on('inquiries');
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('referral_id')->references('id')->on('referrals');
            $table->foreign('agent_id')->references('id')->on('agents');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->foreign('room_category_id')->references('id')->on('room_categories');
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('package_id')->references('id')->on('packages');
            $table->foreign('complimentary_id')->references('id')->on('complimentarys');
            $table->foreign('deposit_id')->references('id')->on('deposits');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
