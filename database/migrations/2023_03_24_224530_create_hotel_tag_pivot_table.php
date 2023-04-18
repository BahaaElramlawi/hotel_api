<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('hotel_tag_pivot', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hotel_id')->unsigned();
            $table->unsignedInteger('hotel_tag_id')->unsigned();
            $table->timestamps();

            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->onDelete('cascade');

            $table->foreign('hotel_tag_id')
                ->references('id')
                ->on('hotel_tags')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hotel_tag_pivot');
    }
};
