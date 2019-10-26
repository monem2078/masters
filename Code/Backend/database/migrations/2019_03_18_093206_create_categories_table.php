<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category_name');
            $table->string('category_name_ar');
            $table->integer('parent_category_id' , false , true)->nullable();
            $table->foreign('parent_category_id')->references('id')->on('categories');
            $table->integer('icon_image_id' , false , true)->nullable();
            $table->foreign('icon_image_id')->references('id')->on('images');
            $table->integer('order');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
