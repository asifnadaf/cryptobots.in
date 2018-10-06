<?php

namespace App\Classes\BittrexBots\Support;

use App\Models\DailyMarketDataModel;
use App\Models\BotRunningStatusModel;
use Log;
use Carbon\Carbon;
use App\Classes\BittrexMarketUtilities;

class RecordDailyMarketSummaryBot
{
    var $className = null;

    public function __construct()
    {
        $this->className = (new \ReflectionClass($this))->getShortName();
    }

    public function dailyMarketSummary()
    {
        $startTime = Carbon::now();
        try {

            $bittrexUtilities = new BittrexMarketUtilities(null,null);
            $marketData = $bittrexUtilities->getMarketSummariesWithDailyPercentageReturnsAndReturnAsArray();
            $this->record($marketData);

            $botRunningStatusData = BotRunningStatusModel::where('className','=',$this->className )->first();
            $botRunningStatusData->lastRun = Carbon::now();
            $botRunningStatusData->runsEvery = 'dailyAt(\'00:01\')';
            $botRunningStatusData->save();

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

        $endTime = Carbon::now();
        $differenceBetweenStartTimeAndEndTime = $startTime->diffInSeconds($endTime, false);
        Log::info(get_class($this).'->'.__FUNCTION__ .' running time: ' . ' start time ' . $startTime . ' end time ' . $endTime . ' $differenceBetweenStartTimeAndEndTime ' . $differenceBetweenStartTimeAndEndTime);
    }

    public function record($updatedData)
    {
        DailyMarketDataModel::insert($updatedData);
        $botRunningStatusData = BotRunningStatusModel::where('className','=',$this->className )->first();
        $botRunningStatusData->dbAffected = 'DailyMarketDataModel';
        $botRunningStatusData->dbLatestUpdatesTimestamp = Carbon::now();
        $botRunningStatusData->save();
    }


}