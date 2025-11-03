<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('room_type_id')->nullable();
            $table->unsignedBigInteger('location_id');
            $table->string('code')->unique();
            $table->string('room_number');
            $table->double('monthly_price',20,2)->default(0);
            $table->double('halfday_price',20,2)->default(0);
            $table->double('daily_price',20,2)->default(0); // this is daily include breakfast
            $table->double('daily_exclude_breakfast_price',20,2)->default(0);
            $table->double('hourly_price',20,2)->default(0);
            $table->double('after_office_hourly_price',20,2)->default(0);
            $table->double('holiday_hourly_price',20,2)->default(0);
            $table->double('bottom_bare_price',20,2)->default(0);
            $table->double('bottom_fitted_price',20,2)->default(0);
            $table->double('bottom_furnished_price',20,2)->default(0);
            $table->double('publish_bare_price',20,2)->default(0);
            $table->double('publish_fitted_price',20,2)->default(0);
            $table->double('publish_furnished_price',20,2)->default(0);
            $table->double('sqm',20,2)->default(0);
            $table->integer('number_of_workstation')->default(0);
            $table->string('default_photo')->nullable();
            $table->enum('tax_status',['no_tax','exclude','include']);
            $table->enum('is_editable_price',['N','Y']);
            $table->enum('has_service_charge',['N','Y']);
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->foreign('parent_id')->references('id')->on('rooms');
            $table->foreign('room_type_id')->references('id')->on('room_types');
            $table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}
