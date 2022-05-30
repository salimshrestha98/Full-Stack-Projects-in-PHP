<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLfCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lf_courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('url');
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->string('authors');
            $table->text('wyl');
            $table->text('description');
            $table->text('wtcif')->nullable();
            $table->string('img_url');
            $table->string('filename');
            $table->string('filesize')->default(1);
            $table->string('cat_1');
            $table->string('cat_2')->nullable();
            $table->string('cat_3')->nullable();
            $table->integer('visits')->default(0)->unsigned();
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
        Schema::dropIfExists('lf_courses');
    }
}
