<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReplaceCountryCodeMobileCountryId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users' , function (Blueprint $table){
            $table->dropColumn('mobile_no_country_code');
            $table->integer('mobile_country_id' , false , true)->after('mobile_no')->nullable();
            $table->foreign('mobile_country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
