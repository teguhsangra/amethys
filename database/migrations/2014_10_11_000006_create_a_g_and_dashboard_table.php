<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAGAndDashboardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('a_g_and_dashboard', function (Blueprint $table) {
            $table->unsignedBigInteger('access_group_id')->index();
            $table->unsignedBigInteger('dashboard_id')->index();
            $table->timestamps();
            $table->unique(['access_group_id', 'dashboard_id']);
            $table->foreign('access_group_id')->references('id')->on('access_groups')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('dashboard_id')->references('id')->on('dashboards')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('a_g_and_dashboard');
    }
}
