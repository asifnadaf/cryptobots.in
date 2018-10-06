<?php

namespace App\Classes\BittrexBots\Support;

use App\Models\BotRunningStatusModel;
use App\Models\BittrexBTCIndexModel;
use App\Models\MarketOddsModel;

use Log;
use Mail;
use Carbon\Carbon;

class RecordBittrexBTCIndexBot
{

    var $className = null;

    public function __construct()
    {
        $this->className = (new \ReflectionClass($this))->getShortName();
    }

    public function setBittrexBTCIndex()
    {
        $startTime = Carbon::now();
        try {

            $this->record();

            $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
            $botRunningStatusData->lastRun = Carbon::now();
            $botRunningStatusData->runsEvery = 'dailyAt(\'00:01\')';
            $botRunningStatusData->save();

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

        $endTime = Carbon::now();
        $differenceBetweenStartTimeAndEndTime = $startTime->diffInSeconds($endTime, false);
        Log::info(get_class($this) . '->' . __FUNCTION__ . ' running time: ' . ' start time ' . $startTime . ' end time ' . $endTime . ' $differenceBetweenStartTimeAndEndTime ' . $differenceBetweenStartTimeAndEndTime);

    }

    public function record()
    {

        try {

            $readabilityFactor = 10000;

            $supportPricesCurrencies = MarketOddsModel::orderBy('supportNLastPercentageDifference', 'asc')->get();

            $sumOf24HoursBackPriceBittrexIndex = 0;
            $sumOfCurrentPriceBittrexIndex = 0;

            $twentyFourHoursBackPriceBTC = 0;
            $CurrentPriceBTC = 0;
            $indexSize = 0;
            foreach ($supportPricesCurrencies as $row){
                if (strcasecmp($row->MarketName, 'USDT-BTC') == 0) {
                    $twentyFourHoursBackPriceBTC = $row->PrevDay;
                    $CurrentPriceBTC = $row->Last;
                }else{
                    $sumOf24HoursBackPriceBittrexIndex = $sumOf24HoursBackPriceBittrexIndex + $row->PrevDay;
                    $sumOfCurrentPriceBittrexIndex = $sumOfCurrentPriceBittrexIndex + $row->Last;
                    $indexSize = $indexSize + 1;
                }
            }

            $sumOf24HoursBackPriceBittrexIndex = $sumOf24HoursBackPriceBittrexIndex * $readabilityFactor;
            $sumOfCurrentPriceBittrexIndex = $sumOfCurrentPriceBittrexIndex * $readabilityFactor;

            $bittrexBTCIndexModel = new BittrexBTCIndexModel();
            $bittrexBTCIndexModel->indexSize = $indexSize;
            $bittrexBTCIndexModel->sumOf24HoursBackPriceBittrexIndex = $sumOf24HoursBackPriceBittrexIndex;
            $bittrexBTCIndexModel->sumOfCurrentPriceBittrexIndex = $sumOfCurrentPriceBittrexIndex;
            $bittrexBTCIndexModel->percentageDifferenceBittrexIndex = ($sumOfCurrentPriceBittrexIndex - $sumOf24HoursBackPriceBittrexIndex)/$sumOf24HoursBackPriceBittrexIndex*100;

            $bittrexBTCIndexModel->twentyFourHoursBackPriceBTC = $twentyFourHoursBackPriceBTC;
            $bittrexBTCIndexModel->CurrentPriceBTC = $CurrentPriceBTC;
            $bittrexBTCIndexModel->percentageDifferenceBTC = ($CurrentPriceBTC - $twentyFourHoursBackPriceBTC)/$twentyFourHoursBackPriceBTC*100;


            $bittrexBTCIndexModel->twentyFourHoursBackProduct = $twentyFourHoursBackPriceBTC * $sumOf24HoursBackPriceBittrexIndex / $readabilityFactor;
            $bittrexBTCIndexModel->CurrentPriceProduct = $CurrentPriceBTC * $sumOfCurrentPriceBittrexIndex / $readabilityFactor;
            $bittrexBTCIndexModel->percentageDifferenceProduct = ($bittrexBTCIndexModel->CurrentPriceProduct - $bittrexBTCIndexModel->twentyFourHoursBackProduct)/$bittrexBTCIndexModel->twentyFourHoursBackProduct*100;
            $bittrexBTCIndexModel->percentageDifferenceProduct;

            $bittrexBTCIndexModel->created_at = Carbon::now();
            $bittrexBTCIndexModel->updated_at = Carbon::now();
            $bittrexBTCIndexModel->save();

            $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
            $botRunningStatusData->dbAffected = 'BittrexBTCIndexModel';
            $botRunningStatusData->dbLatestUpdatesTimestamp = Carbon::now();
            $botRunningStatusData->save();


        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception:' . $exception);
        }

    }



}