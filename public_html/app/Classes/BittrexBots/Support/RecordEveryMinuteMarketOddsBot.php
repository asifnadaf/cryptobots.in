<?php

namespace App\Classes\BittrexBots\Support;

use App\Classes\BittrexMarketOddsUtilities;
use App\Models\MarketOddsModel;
use App\Models\BotRunningStatusModel;
use Log;
use Carbon\Carbon;

class RecordEveryMinuteMarketOddsBot
{
    var $className = null;

    public function __construct()
    {
        $this->className = (new \ReflectionClass($this))->getShortName();
    }


    public function recordEveryMinuteMarketOdds()
    {
        $startTime = Carbon::now();
        try {
            $bittrexExpectedValueUtilities = new BittrexMarketOddsUtilities();
            $newData = $bittrexExpectedValueUtilities->getExpectedValuesOfAllAltcoins();
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
        $dbOldData = MarketOddsModel::all();

        $count = 0;
        try {
            foreach ($dbOldData as $oldDataRow) {

                foreach ($newData as $newDataRow) {
                    if (strcasecmp($oldDataRow->MarketName, $newDataRow->MarketName) == 0) {

                        $dbRowToBeUpdated = MarketOddsModel::find($oldDataRow->id);

                        $dbRowToBeUpdated->MarketName = $newDataRow->MarketName;
                        $dbRowToBeUpdated->High = $newDataRow->High;
                        $dbRowToBeUpdated->Low = $newDataRow->Low;
                        $dbRowToBeUpdated->Volume = $newDataRow->Volume;
                        $dbRowToBeUpdated->Last = $newDataRow->Last;
                        $dbRowToBeUpdated->BaseVolume = $newDataRow->BaseVolume;
                        $dbRowToBeUpdated->averageBaseVolume = $newDataRow->averageBaseVolume;
                        $dbRowToBeUpdated->pumpCounts = $newDataRow->pumpCounts;
                        $dbRowToBeUpdated->TimeStamp = $newDataRow->TimeStamp;
                        $dbRowToBeUpdated->Bid = $newDataRow->Bid;
                        $dbRowToBeUpdated->Ask = $newDataRow->Ask;
                        $dbRowToBeUpdated->OpenBuyOrders = $newDataRow->OpenBuyOrders;
                        $dbRowToBeUpdated->OpenSellOrders = $newDataRow->OpenSellOrders;
                        $dbRowToBeUpdated->PrevDay = $newDataRow->PrevDay;
                        $dbRowToBeUpdated->Created = $newDataRow->Created;
                        $dbRowToBeUpdated->exchangeName = $newDataRow->exchangeName;
                        $dbRowToBeUpdated->percentChange = $newDataRow->percentChange;

                        $dbRowToBeUpdated->lowestPrice = $newDataRow->lowestPrice;
                        $dbRowToBeUpdated->averagePrice = $newDataRow->averagePrice;
                        $dbRowToBeUpdated->averageReturn = $newDataRow->averageReturn;
                        $dbRowToBeUpdated->numberOfRows = $newDataRow->numberOfRows;

                        $dbRowToBeUpdated->supportPriceConfidence = $newDataRow->supportPriceConfidence;
                        $dbRowToBeUpdated->needToCountForSupportPrice = $newDataRow->needToCountForSupportPrice;
                        $dbRowToBeUpdated->supportPrice = $newDataRow->supportPrice;

                        $dbRowToBeUpdated->equalOddsConfidence = $newDataRow->equalOddsConfidence;
                        $dbRowToBeUpdated->needToCountForEqualOddsPrice = $newDataRow->needToCountForEqualOddsPrice;
                        $dbRowToBeUpdated->equalOddsPrice = $newDataRow->equalOddsPrice;
                        $dbRowToBeUpdated->supportAndEqualOddsRatio = $newDataRow->supportAndEqualOddsRatio;

                        $dbRowToBeUpdated->resistancePriceConfidence = $newDataRow->resistancePriceConfidence;
                        $dbRowToBeUpdated->needToCountForResistancePrice = $newDataRow->needToCountForResistancePrice;
                        $dbRowToBeUpdated->resistancePrice = $newDataRow->resistancePrice;
                        $dbRowToBeUpdated->supportAndResistanceOddsRatio = $newDataRow->supportAndResistanceOddsRatio;

                        $dbRowToBeUpdated->supportNLastPercentageDifference = $newDataRow->supportNLastPercentageDifference;
                        $dbRowToBeUpdated->resistanceNSupportPercentageDifference = $newDataRow->resistanceNSupportPercentageDifference;

                        $dbRowToBeUpdated->expectedPrice = $newDataRow->expectedPrice;
                        $dbRowToBeUpdated->expectedReturn = $newDataRow->expectedReturn;

                        $dbRowToBeUpdated->created_at = Carbon::now();
                        $dbRowToBeUpdated->updated_at = Carbon::now();

                        $dbRowToBeUpdated->save();
                        $count++;
                        break;
                    }
                }

            }
            if($count>0){
                $botRunningStatusData = BotRunningStatusModel::where('className','=',$this->className )->first();
                $botRunningStatusData->dbAffected = 'MarketOddsModel';
                $botRunningStatusData->dbLatestUpdatesTimestamp = Carbon::now();
                $botRunningStatusData->save();
            }

        } catch (Exception $exception) {
            Log::info(get_class($this).'->'.__FUNCTION__ .' exception: ' . 'exception' . $exception);
        }

    }


}