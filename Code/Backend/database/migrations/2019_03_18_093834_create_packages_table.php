<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('master_id', false, true);
            $table->foreign('master_id')->references('id')->on('masters');
            $table->integer('category_id', false, true);
            $table->foreign('category_id')->references('id')->on('categories');
            $table->string('title');
            $table->string('description');
            $table->double('price');
            $table->integer('currency_id', false, true);
            $table->foreign('currency_id')->references('id')->on('currencies');
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
        Schema::dropIfExists('packages');
    }
}
