<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_targets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('code')->unique();
            $table->integer('year');
            $table->integer('month');
            $table->double('total_target',20,2)->default(0);
            $table->integer('total_target_vo')->default(0);
            $table->integer('total_target_so')->default(0);
            $table->string('discard_or_cancel_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['employee_id', 'year', 'month']);
            $table->string('draft_by')->nullable();
            $table->string('posting_by')->nullable();
            $table->string('discard_by')->nullable();
            $table->string('complete_by')->nullable();
            $table->string('cancel_by')->nullable();
            $table->foreign('status_id')->references('id')->on('statuses');
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
        Schema::dropIfExists('sales_targets');
    }
}
