<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('hotel_images', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hotel_id')->unsigned();
            $table->string('image_url');
            $table->timestamps();

            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hotel_images');
    }
};
