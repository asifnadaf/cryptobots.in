<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBittrexTriangularArbitrageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bittrex', function (Blueprint $table) {
            $table->increments('id');

            $table->string('firstCurrencyPair');
            $table->float('firstCurrencyPairTotalPrice');
            $table->float('firstCurrencyPairAveragePricePerUnit');
            $table->float('firstCurrencyPairQuantity');

            $table->string('secondCurrencyPair');
            $table->float('secondCurrencyPairTotalPrice');
            $table->float('secondCurrencyPairAveragePricePerUnit');
            $table->float('secondCurrencyPairQuantity');

            $table->string('thirdCurrencyPair');
            $table->float('thirdCurrencyPairTotalPrice');
            $table->float('thirdCurrencyPairAveragePricePerUnit');
            $table->float('thirdCurrencyPairQuantity');

            $table->string('grossProfit');
            $table->float('grossProfitPercent');
            $table->float('commission');
            $table->float('commissionPercentage');
            $table->float('netProfit');
            $table->float('netProfitPercentage');
            $table->integer('counter');
            $table->boolean('flag');

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
//        Schema::dropIfExists('bittrex');
    }
}
