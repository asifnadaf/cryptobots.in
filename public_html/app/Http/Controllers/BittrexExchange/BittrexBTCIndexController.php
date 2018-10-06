<?php

namespace App\Http\Controllers\BittrexExchange;

use App\Models\BittrexBTCIndexModel;
use App\Models\MarketOddsModel;
use Log;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;

class BittrexBTCIndexController extends Controller
{

    public function index()
    {

        $supportPricesCurrencies = MarketOddsModel::orderBy('supportNLastPercentageDifference', 'asc')->get();
        $readabilityFactor = 10000;

        $sumOf24HoursBackPriceBittrexIndex = 0;
        $sumOfCurrentPriceBittrexIndex = 0;

        $twentyFourHoursBackPriceBTC = 0;
        $CurrentPriceBTC = 0;
        foreach ($supportPricesCurrencies as $row){
            if (strcasecmp($row->MarketName, 'USDT-BTC') == 0) {
                $twentyFourHoursBackPriceBTC = $row->PrevDay;
                $CurrentPriceBTC = $row->Last;
            }else{
                $sumOf24HoursBackPriceBittrexIndex = $sumOf24HoursBackPriceBittrexIndex + $row->PrevDay;
                $sumOfCurrentPriceBittrexIndex = $sumOfCurrentPriceBittrexIndex + $row->Last;
            }
        }

        $sumOf24HoursBackPriceBittrexIndex = $sumOf24HoursBackPriceBittrexIndex * $readabilityFactor;
        $sumOfCurrentPriceBittrexIndex = $sumOfCurrentPriceBittrexIndex * $readabilityFactor;

        $currentData = (object)[];
        $currentData->sumOf24HoursBackPriceBittrexIndex = $sumOf24HoursBackPriceBittrexIndex;
        $currentData->sumOfCurrentPriceBittrexIndex = $sumOfCurrentPriceBittrexIndex;
        $currentData->percentageDifferenceBittrexIndex = ($sumOfCurrentPriceBittrexIndex - $sumOf24HoursBackPriceBittrexIndex) / $sumOf24HoursBackPriceBittrexIndex * 100;

        $currentData->twentyFourHoursBackPriceBTC = $twentyFourHoursBackPriceBTC;
        $currentData->CurrentPriceBTC = $CurrentPriceBTC;
        $currentData->percentageDifferenceBTC = ($CurrentPriceBTC - $twentyFourHoursBackPriceBTC)/$twentyFourHoursBackPriceBTC*100;

        $bittrexBTCIndexData = BittrexBTCIndexModel::orderBy('created_at', 'desc')->get();

        return View::make('bittrex/bittrexbtcindex/index', compact('currentData', 'bittrexBTCIndexData'));
    }

}
