<?php

namespace App\Classes\BittrexBots\Support;

use App\Classes\CurrenciesUtilities;
use App\Models\BotRunningStatusModel;
use App\Models\BaseCurrenciesRateModel;
use Log;
use Carbon\Carbon;

class RecordBaseCurrenciesRateBot
{
    var $className = null;

    public function __construct()
    {
        $this->className = (new \ReflectionClass($this))->getShortName();
    }

    public function recordBaseCurrencies()
    {
        $startTime = Carbon::now();

        try {

            $bittrexUtilities = new CurrenciesUtilities(null,null);
            $USDBTCRate = $bittrexUtilities->getUSDBTCRateFromMarket();
            $TetherBTCRate = $bittrexUtilities->getTetherBTCRateFromMarket();

            $this->record($USDBTCRate, $TetherBTCRate);

            $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
            $botRunningStatusData->lastRun = Carbon::now();
            $botRunningStatusData->runsEvery = 'Every minute';
            $botRunningStatusData->save();

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

        $endTime = Carbon::now();
        $differenceBetweenStartTimeAndEndTime = $startTime->diffInSeconds($endTime, false);
        Log::info(get_class($this) . '->' . __FUNCTION__ . ' running time: ' . ' start time ' . $startTime . ' end time ' . $endTime . ' $differenceBetweenStartTimeAndEndTime ' . $differenceBetweenStartTimeAndEndTime);
    }

    public function record($USDBTCRate, $TetherBTCRate)
    {
        $baseCurrencyRow = [];
        $baseCurrencyRow['USD_BTC_Rate'] = $USDBTCRate;
        $baseCurrencyRow['Tether_BTC_Rate'] = $TetherBTCRate;
        $baseCurrencyRow['created_at'] = Carbon::now();
        $baseCurrencyRow['updated_at'] = Carbon::now();
        BaseCurrenciesRateModel::insert($baseCurrencyRow);

        $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
        $botRunningStatusData->dbAffected = 'BaseCurrenciesRate';
        $botRunningStatusData->dbLatestUpdatesTimestamp = Carbon::now();
        $botRunningStatusData->save();
    }


}