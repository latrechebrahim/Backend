<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('specialty')->nullable();
            $table->string('email')->unique();
            $table->string('phonenumber')->nullable();
            $table->string('date_birth')->nullable();
            $table->string('path')->nullable();
            $table->string('password');
            $table->string('confirmpassword');
            $table->boolean('admin');
            $table->boolean('isAvailable')->nullable();
            $table->rememberToken();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctors');
    }
};
