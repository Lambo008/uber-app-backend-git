<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('country_code',10)->nullable();
            $table->bigInteger('mobile',11)->nullable();
            $table->integer('otp',11)->nullable();
            $table->string('status',10)->nullable();
            $table->string('image')->nullable();
            $table->float('latitude',10,6)->nullable();
            $table->float('longitude',10,6)->nullable();
            $table->string('fcm_token')->nullable();
            $table->string('google_token')->nullable();
            $table->string('facebook_token')->nullable();
            $table->string('login_type')->nullable();
            $table->integer('wallet')->nullable();
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
        Schema::drop('users');
    }
}
