<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('hotel_regions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('region_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hotel_regions');
    }
};
