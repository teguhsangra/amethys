<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSAAndMMTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_a_and_m_m', function (Blueprint $table) {
            $table->unsignedBigInteger('sales_activity_id')->index();
            $table->unsignedBigInteger('marketing_material_id')->index();
            $table->timestamps();
            $table->primary(['sales_activity_id', 'marketing_material_id']);
            $table->foreign('sales_activity_id')->references('id')->on('sales_activities')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('marketing_material_id')->references('id')->on('marketing_materials')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('s_a_and_m_m');
    }
}
