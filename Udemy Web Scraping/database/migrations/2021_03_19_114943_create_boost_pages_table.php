<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoostPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boost_pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('url');
            $table->integer('clicks')->unsigned()->default(0);
            $table->string('phrases');
            $table->string('serp_img');
            $table->string('location');
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
        Schema::dropIfExists('boost_pages');
    }
}
