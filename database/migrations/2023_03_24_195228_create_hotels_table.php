<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('host_id')->unsigned();
            $table->unsignedInteger('location_id')->unsigned();
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->decimal('rate', 4, 2);
            $table->text('description');
            $table->timestamps();

            $table->foreign('host_id')
                ->references('id')
                ->on('hosts')
                ->onDelete('cascade');

            $table->foreign('location_id')
                ->references('id')
                ->on('hotel_locations')
                ->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('hotels');
    }
};
