<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAGAndModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('a_g_and_module', function (Blueprint $table) {
            $table->unsignedBigInteger('access_group_id')->index();
            $table->unsignedBigInteger('module_id')->index();
            $table->boolean('read')->default(0);
            $table->boolean('create')->default(0);
            $table->boolean('update')->default(0);
            $table->boolean('delete')->default(0);
            $table->boolean('isExec')->default(0);
            $table->boolean('showDataByStructure')->default(0);
            $table->timestamps();
            $table->unique(['access_group_id', 'module_id']);
            $table->foreign('access_group_id')->references('id')->on('access_groups')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('a_g_and_module');
    }
}
