<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('hotel_facility_pivot', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('hotel_id')->unsigned();
            $table->unsignedInteger('hotel_facility_id')->unsigned();
            $table->timestamps();

            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->onDelete('cascade');

            $table->foreign('hotel_facility_id')
                ->references('id')
                ->on('hotel_facilities')
                ->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('hotel_facility_pivot');
    }
};
