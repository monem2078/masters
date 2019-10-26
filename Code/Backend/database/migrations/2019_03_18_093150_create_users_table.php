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
            $table->string('name');
            $table->string('username');
            $table->string('password')->nullable();
            $table->string('email')->nullable();
            $table->integer('role_id' , false , true);
            $table->foreign('role_id')->references('id')->on('roles');
            $table->integer('country_id' , false , true)->nullable();
            $table->foreign('country_id')->references('id')->on('countries');
            $table->integer('city_id' , false , true)->nullable();
            $table->foreign('city_id')->references('id')->on('cities');
            $table->integer('profile_image_id' , false , true)->nullable();
            $table->foreign('profile_image_id')->references('id')->on('images');
            $table->string('mobile_no')->nullable();
            $table->integer('mobile_country_id' , false , true)->nullable();
            $table->foreign('mobile_country_id')->references('id')->on('countries');
            $table->integer('gender_id' , false , true)->nullable();
            $table->foreign('gender_id')->references('id')->on('genders');
            $table->integer('platform_id' , false , true)->nullable();
            $table->foreign('platform_id')->references('id')->on('platforms');
            $table->string('os_version')->nullable();
            $table->integer('language_id' , false , true)->default(1);
            $table->foreign('language_id')->references('id')->on('languages');
            $table->boolean('allow_notification')->default(1);
            $table->string('oauth_provider')->nullable();
            $table->string('oauth_uid')->nullable();
            $table->boolean('is_verified')->default(0);
            $table->rememberToken();
            $table->softDeletes();
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
