<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('name');
            $table->timestamps();
        });

        // Insert static admin data
        $data = [
            'email' => 'admin@example.com',
            'password' => Hash::make('123456789'),
            'name' => 'Admin',
            'created_at' => DB::raw('CURRENT_TIMESTAMP'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP')
        ];

        DB::table('admins')->insert($data);

    }

    public function down()
    {
        Schema::dropIfExists('admins');
    }
};
