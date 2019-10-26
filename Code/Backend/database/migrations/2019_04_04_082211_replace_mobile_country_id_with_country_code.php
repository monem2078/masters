<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReplaceMobileCountryIdWithCountryCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('users' , function (Blueprint $table){
           $table->dropForeign('users_mobile_country_id_foreign');
           $table->dropColumn('mobile_country_id');
           $table->string('mobile_no_country_code')->nullable()->after('profile_image_id');
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
