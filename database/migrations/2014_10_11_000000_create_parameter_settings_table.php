<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParameterSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parameter_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('int_value')->nullable();
            $table->double('double_value',10,10)->nullable();
            $table->string('string_value')->nullable();
            $table->text('text_value')->nullable();
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parameter_settings');
    }
}
