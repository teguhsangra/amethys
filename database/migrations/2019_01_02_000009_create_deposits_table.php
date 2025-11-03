<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('code')->unique();
            $table->enum('type_security_deposit',['IN','OUT']); // IN = Add Deposit, OUT = Out Deposit
            $table->enum('category',['security_deposit','customer_credit', 'booking_fee', 'down_payment', 'clearing_deposit']); 
            $table->double('total_deposit',20,2);
            $table->double('stamp_duty',20,2)->default(0);
            $table->double('total_paid')->default(0);
            $table->date('due_date')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('payment_status',['NP','HP','PA']); // NP = Not Paid, HP = Half Paid, PA = Paid
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
            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deposits');
    }
}
