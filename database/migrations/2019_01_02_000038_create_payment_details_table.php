<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->unsignedBigInteger('non_cash_id')->nullable();
            $table->enum('payment_type',['CASH','NON_CASH','DEPOSIT','WHT','LG','OTHER']);
            $table->double('amount',20,2)->default(0);
            $table->string('bank_issuer')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->enum('card_type',['NO_CARD','CREDIT','DEBIT'])->nullable();
            $table->string('card_holder_name')->nullable();
            $table->string('card_number')->nullable();
            $table->string('batch')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->foreign('payment_id')->references('id')->on('payments');
            $table->foreign('bank_account_id')->references('id')->on('bank_accounts');
            $table->foreign('non_cash_id')->references('id')->on('non_cashes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_details');
    }
}
