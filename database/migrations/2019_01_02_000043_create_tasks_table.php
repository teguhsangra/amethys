<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id'); // user yg membuat task
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('employee_id'); // diisi sesuai dengan id employee yg diberi tugas
            $table->unsignedBigInteger('previous_id')->nullable();
            $table->unsignedBigInteger('ticketing_id')->nullable();
            $table->unsignedBigInteger('task_subject_id')->nullable();
            $table->string('code')->unique();
            $table->enum('is_escalated', ['N', 'Y']);
            $table->enum('is_closed', ['N', 'Y']);
            $table->string('subject')->nullable(); // diisi dengan judul tugas
            $table->text('remarks');
            $table->dateTime('estimated_done_at')->nullable(); // dapat diisi dengan perkiraan waktu selesai
            $table->timestamps();
            $table->softDeletes();
            $table->dateTime('closed_at')->nullable(); // diisi ketika task selesai di kerjakan
            $table->dateTime('escalated_at')->nullable(); // diisi ketika task tidak selesai di kerjakan dan harus di eskalasi
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('previous_id')->references('id')->on('tasks');
            $table->foreign('ticketing_id')->references('id')->on('ticketings');
            $table->foreign('task_subject_id')->references('id')->on('task_subjects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
