<?php

namespace App\Classes\BittrexBots\Support;

use App\Classes\BittrexSupportAndResistanceUtilities;
use App\Models\SupportResistanceModel;
use App\Models\BotRunningStatusModel;
use Log;
use Carbon\Carbon;

class RecordEveryMinuteSupportResistanceBot
{
    var $className = null;

    public function __construct()
    {
        $this->className = (new \ReflectionClass($this))->getShortName();
    }


    public function recordEveryMinuteSupportResistance()
    {
        $startTime = Carbon::now();
        try {
            $bittrexSupportAndResistanceUtilities = new BittrexSupportAndResistanceUtilities();
            $newData = $bittrexSupportAndResistanceUtilities->getSupportAndResistancePriceOfAllAltcoins();
            $this->record($newData);

            $botRunningStatusData = BotRunningStatusModel::where('className','=',$this->className )->first();
            $botRunningStatusData->runsEvery = 'Every minute';
            $botRunningStatusData->lastRun = Carbon::now();
            $botRunningStatusData->save();
        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

        $endTime = Carbon::now();
        $differenceBetweenStartTimeAndEndTime = $startTime->diffInSeconds($endTime, false);
        Log::info(get_class($this).'->'.__FUNCTION__ .' running time: ' . ' start time ' . $startTime . ' end time ' . $endTime . ' $differenceBetweenStartTimeAndEndTime ' . $differenceBetweenStartTimeAndEndTime);
    }

    public function record($newData)
    {
        $dbOldData = SupportResistanceModel::all();

        $count = 0;
        try {
            foreach ($dbOldData as $oldDataRow) {

                foreach ($newData as $newDataRow) {
                    if (strcasecmp($oldDataRow->MarketName, $newDataRow->MarketName) == 0) {

                        $dbRowToBeUpdated = SupportResistanceModel::find($oldDataRow->id);

                        $dbRowToBeUpdated->MarketName = $newDataRow->MarketName;
                        $dbRowToBeUpdated->High = $newDataRow->High;
                        $dbRowToBeUpdated->Low = $newDataRow->Low;
                        $dbRowToBeUpdated->Volume = $newDataRow->Volume;
                        $dbRowToBeUpdated->Last = $newDataRow->Last;
                        $dbRowToBeUpdated->BaseVolume = $newDataRow->BaseVolume;
                        $dbRowToBeUpdated->TimeStamp = $newDataRow->TimeStamp;
                        $dbRowToBeUpdated->Bid = $newDataRow->Bid;
                        $dbRowToBeUpdated->Ask = $newDataRow->Ask;
                        $dbRowToBeUpdated->OpenBuyOrders = $newDataRow->OpenBuyOrders;
                        $dbRowToBeUpdated->OpenSellOrders = $newDataRow->OpenSellOrders;
                        $dbRowToBeUpdated->PrevDay = $newDataRow->PrevDay;
                        $dbRowToBeUpdated->Created = $newDataRow->Created;
                        $dbRowToBeUpdated->exchangeName = $newDataRow->exchangeName;
                        $dbRowToBeUpdated->percentChange = $newDataRow->percentChange;
                        $dbRowToBeUpdated->averageSupportPrice = $newDataRow->averageSupportPrice;
                        $dbRowToBeUpdated->supportCount = $newDataRow->supportCount;
                        $dbRowToBeUpdated->totalSupportCount = $newDataRow->totalSupportCount;
                        $dbRowToBeUpdated->supportStrength = $newDataRow->supportStrength;
                        $dbRowToBeUpdated->minValue = $newDataRow->minValue;
                        $dbRowToBeUpdated->supportThreshold = $newDataRow->supportThreshold;
                        $dbRowToBeUpdated->avgSupportNLastdifference = $newDataRow->avgSupportNLastdifference;
                        $dbRowToBeUpdated->avgResistanceNLastdifference = $newDataRow->avgResistanceNLastdifference;
                        $dbRowToBeUpdated->averageResistancePrice = $newDataRow->averageResistancePrice;
                        $dbRowToBeUpdated->averageSupportAndAverageResistancePercentageDifference = ($dbRowToBeUpdated->averageResistancePrice - $dbRowToBeUpdated->averageSupportPrice)/$dbRowToBeUpdated->averageSupportPrice * 100;
                        $dbRowToBeUpdated->resistanceCount = $newDataRow->resistanceCount;
                        $dbRowToBeUpdated->totalResistanceCount = $newDataRow->totalResistanceCount;
                        $dbRowToBeUpdated->resistanceStrength = $newDataRow->resistanceStrength;
                        $dbRowToBeUpdated->maxValue = $newDataRow->maxValue;
                        $dbRowToBeUpdated->resistanceThreshold = $newDataRow->resistanceThreshold;
                        $dbRowToBeUpdated->updated_at = Carbon::now();

                        if($newDataRow->Last > $dbRowToBeUpdated->averageSupportPrice){
                            $dbRowToBeUpdated->isBelowSupportPrice = 'No';
                        }

                        if($newDataRow->Last < $dbRowToBeUpdated->averageResistancePrice){
                            $dbRowToBeUpdated->isAboveResistancePrice = 'No';
                        }
                        $dbRowToBeUpdated->save();
                        $count++;
                        break;
                    }
                }

            }
            if($count>0){
                $botRunningStatusData = BotRunningStatusModel::where('className','=',$this->className )->first();
                $botRunningStatusData->dbAffected = 'SupportResistanceModel';
                $botRunningStatusData->dbLatestUpdatesTimestamp = Carbon::now();
                $botRunningStatusData->save();
            }

        } catch (Exception $exception) {
            Log::info(get_class($this).'->'.__FUNCTION__ .' exception: ' . 'exception' . $exception);
        }

    }


}