<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNepseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nepses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('day');
            $table->string('symbol');
            $table->float('close');
            $table->float('open');
            $table->float('high');
            $table->float('low');
            $table->float('change');
            $table->string('qty');
            $table->string('code')->nullable();
            $table->float('index_1')->nullable()->default(0);
            $table->float('index_2')->nullable()->default(0);
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
        Schema::dropIfExists('nepse');
    }
}
