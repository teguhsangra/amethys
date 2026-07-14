<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->unsignedBigInteger('proforma_id')->nullable();
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('billing_reminder_id')->nullable();
            $table->string('code')->unique();
            $table->integer('last_number')->default(0);
            $table->string('other_code')->nullable();
            $table->enum('detail_status',['Y','N']); // Y = Yes, N = No
            $table->enum('custom_status',['N','Y']); // Y = Yes, N = No
            $table->double('total_price',20,2)->default(0);
            $table->double('total_service_charge',20,2)->default(0);
            $table->double('total_tax_price',20,2)->default(0);
            $table->double('stamp_duty',20,2)->default(0);
            $table->double('round_price', 20, 2)->default(0);
            $table->double('total_deposit', 20, 2)->default(0);
            $table->double('total_penalty',20,2)->default(0);
            $table->double('total_paid',20,2)->default(0);
            $table->date('invoice_date');
            $table->date('due_date');
            $table->text('desc')->nullable();
            $table->text('reason')->nullable();
            $table->enum('payment_status',['NP','HP','PA']); // NP = Not Paid, HP = Half Paid, PA = Paid
            $table->enum('address_status',['location', 'customer']);
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
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('bank_account_id')->references('id')->on('bank_accounts');
            $table->foreign('proforma_id')->references('id')->on('proformas');
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('billing_reminder_id')->references('id')->on('billing_reminders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
