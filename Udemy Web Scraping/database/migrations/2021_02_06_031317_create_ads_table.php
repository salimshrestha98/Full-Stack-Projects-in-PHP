<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->string('site');
            $table->string('product');
            $table->string('target')->default('GLOBAL');
            $table->text('categories')->nullable();
            $table->string('location')->nullable();
            $table->integer('width')->unsigned();
            $table->integer('height')->unsigned();
            $table->text('content');
            $table->text('remarks');
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
        Schema::dropIfExists('ads');
    }
}
