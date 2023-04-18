<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('hotel_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('region_id')->unsigned();
            $table->string('location_name');
            $table->double('longitude');
            $table->double('latitude');
            $table->timestamps();

            $table->foreign('region_id')
                ->references('id')
                ->on('hotel_regions')
                ->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('hotel_locations');
    }
};
