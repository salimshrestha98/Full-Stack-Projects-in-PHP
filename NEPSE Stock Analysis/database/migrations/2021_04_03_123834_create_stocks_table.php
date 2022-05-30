<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('symbol');
            $table->string('name');
            $table->string('sector');
            $table->float('ltp');
            $table->float('52_high');
            $table->float('52_low');
            $table->float('120_avg');
            $table->float('yield');
            $table->float('eps');
            $table->float('pe_ratio');
            $table->float('book_value');
            $table->float('pbv_ratio');
            $table->float('dividend')->default(0);
            $table->float('bonus')->default(0);
            $table->float('return')->default(0);
            $table->string('listed_shares');
            $table->string('market_cap');
            $table->string('30_day_avg_volume');
            $table->float('index_1')->nullable();
            $table->float('index_2')->nullable();
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
        Schema::dropIfExists('stocks');
    }
}
