<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('country_code',10)->nullable();
            $table->bigInteger('mobile',11)->nullable();
            $table->integer('otp',11)->nullable();
            $table->string('status',10)->nullable();
            $table->tinyInteger('has_fleet_owner',1)->nullable();
            $table->Integer('fleet_owner_id')->nullable();
            $table->Integer('service_category_id')->nullable();
            $table->Integer('service_sub_category_id')->nullable();
            $table->tinyInteger('is_blocked',1)->nullable();
            $table->Integer('fleet_owner_id')->nullable();
            $table->string('status',10)->nullable();
            $table->string('image')->nullable();
            $table->float('latitude',10,6)->nullable();
            $table->float('longitude',10,6)->nullable();
            $table->string('fcm_token')->nullable();
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
       Schema::drop('providers');
    }
}
