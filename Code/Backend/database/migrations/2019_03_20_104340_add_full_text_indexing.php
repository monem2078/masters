<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFullTextIndexing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE categories ADD FULLTEXT (category_name, category_name_ar)');
        DB::statement('ALTER TABLE packages ADD FULLTEXT (title, description)');
        DB::statement('ALTER TABLE masters ADD FULLTEXT (headline, about_headline , about_text)');
        DB::statement('ALTER TABLE countries ADD FULLTEXT (country_name, country_name_ar)');
        DB::statement('ALTER TABLE cities ADD FULLTEXT (city_name, city_name_ar)');
        DB::statement('ALTER TABLE users ADD FULLTEXT (name)');
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
