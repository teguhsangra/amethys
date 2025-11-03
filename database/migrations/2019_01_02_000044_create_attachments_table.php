<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ticketing_id')->nullable();
            $table->unsignedBigInteger('ticketing_reply_id')->nullable();
            $table->unsignedBigInteger('task_id')->nullable();
            $table->string('attachment');
            $table->timestamps();
            $table->foreign('ticketing_id')->references('id')->on('ticketings')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('ticketing_reply_id')->references('id')->on('ticketing_replies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachments');
    }
}
