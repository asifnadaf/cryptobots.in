<?php

namespace App\Http\Controllers\BittrexExchange;

use Log;
use Mail;
use Carbon\Carbon;

use App\Models\DailyMarketDataModel;
use App\Http\Controllers\Controller;

use Charts;
use App\Http\Requests;

//This controller takes care of altcoin charts
class AltcoinChartController extends Controller
{

    public function index($marketName)
    {

        $start = Carbon::now();
        $start->subDay(90);
        $start = Carbon::createFromFormat('Y-m-d G:i:s', $start);

        $end = Carbon::now();
        $end = Carbon::createFromFormat('Y-m-d G:i:s', $end);
        $altcoinHistoricalData = DailyMarketDataModel::whereBetween('created_at', [$start, $end])->where('marketName', '=', 'USDT-BTC')->orderBy('created_at', 'asc')->get();

        foreach ($altcoinHistoricalData as $row) {
            $BTCPriceInUSDT[] =  $row->Last;
            $date = Carbon::createFromFormat('Y-m-d G:i:s', $row->created_at);
            $BTCPriceInUSDTLabel[] =$date->format('d-M');
        }
        $title = "BTC price in USDT";
        $btcInUSDTChart = Charts::multi('line', 'highcharts')
            // Setup the chart settings
            ->title($title)
            // A dimension of 0 means it will take 100% of the space
            ->dimensions(0, 500)// Width x Height
            // This defines a preset of colors already done:)
            ->template("material")
            // You could always set them manually
             ->colors(['#2196F3'])
            // Setup the diferent datasets (this is a multi chart)
            ->dataset($title, $BTCPriceInUSDT)
            // Setup what the values mean
            ->labels($BTCPriceInUSDTLabel);



        $BTCPrefix = 'BTC-';
        $altcoinName = str_replace($BTCPrefix, "", $marketName);
        $altcoinHistoricalData = DailyMarketDataModel::whereBetween('created_at', [$start, $end])->where('marketName', '=', $marketName)->orderBy('created_at', 'asc')->get();

        foreach ($altcoinHistoricalData as $row) {
            $AltcoinPriceInBTC[] =  $row->Last;
            $date = Carbon::createFromFormat('Y-m-d G:i:s', $row->created_at);
            $AltcoinPriceInBTCLabel[] =$date->format('d-M');
            $AltcoinPriceInUSDTLabel[] =$date->format('d-M');

            $start = Carbon::createFromFormat('Y-m-d G:i:s', $row->created_at);
            $end = Carbon::createFromFormat('Y-m-d G:i:s', $row->created_at);
            $start->subHours(2);
            $end->addHours(2);
            $BTCUSDTRateRow = null;
            $BTCUSDTRateRow = DailyMarketDataModel::whereBetween('created_at', [$start, $end])->where('marketName', '=', 'USDT-BTC')->orderBy('created_at', 'asc')->first();
            if(count($BTCUSDTRateRow)>0){
                $AltcoinPriceInUSDT[] =  $row->Last*$BTCUSDTRateRow->Last;
            }
        }

        $title = $altcoinName." price in Satoshi";
        $altcoinInBTCChart = Charts::multi('line', 'highcharts')
            // Setup the chart settings
            ->title($title)
            // A dimension of 0 means it will take 100% of the space
            ->dimensions(0, 500)// Width x Height
            // This defines a preset of colors already done:)
            ->template("material")
            // You could always set them manually
            ->colors(['#F44336'])
            // Setup the diferent datasets (this is a multi chart)
            ->dataset($title, $AltcoinPriceInBTC)
            // Setup what the values mean
            ->labels($AltcoinPriceInBTCLabel);


        $title = $altcoinName." price in USDT";
        $altcoinInUSDTChart = Charts::multi('line', 'highcharts')
            // Setup the chart settings
            ->title($title)
            // A dimension of 0 means it will take 100% of the space
            ->dimensions(0, 500)// Width x Height
            // This defines a preset of colors already done:)
            ->template("material")
            // You could always set them manually
            ->colors(['#FFC107'])
            // Setup the diferent datasets (this is a multi chart)
            ->dataset($title, $AltcoinPriceInUSDT)
            // Setup what the values mean
            ->labels($AltcoinPriceInUSDTLabel);

//        return Charts::multi('line', 'highcharts')->settings();

        return view('bittrex/odds/altcoinchart/index', ['btcInUSDTChart' => $btcInUSDTChart,'altcoinInBTCChart' => $altcoinInBTCChart,'altcoinInUSDTChart' => $altcoinInUSDTChart,'marketName' => $marketName]);

    }


}
