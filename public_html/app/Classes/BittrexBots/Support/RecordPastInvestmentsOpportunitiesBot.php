<?php

namespace App\Classes\BittrexBots\Support;

use App\Models\MarketOddsModel;
use App\Models\BotRunningStatusModel;
use App\Models\BotSettingsModel;
use App\Models\PastInvestmentsOpportunitiesModel;
use Log;
use Mail;
use Carbon\Carbon;
use Mockery\Exception;

class RecordPastInvestmentsOpportunitiesBot
{

    var $BTCPrefix = 'BTC-';
    var $maximumBTCToInvestFactor = 0.1; // Diversification. Only certain percentage of available BTCs are invested per currency
    var $className = null;

    public function __construct()
    {
        $this->className = (new \ReflectionClass($this))->getShortName();
    }

    public function recordPastInvestmentsOpportunities()
    {
        try {

            $startTime = Carbon::now();

            $botSettingRow = BotSettingsModel::first();
            $minimumVolumeOfBaseCurrencyBTC = $botSettingRow->minimumVolumeOfBaseCurrencyBTC;
            $supportAndEqualOddsRatio = $botSettingRow->supportAndEqualOddsRatio;

            $currentSupportPricesCurrencies = MarketOddsModel::whereRaw('Ask <=supportPrice')->where('supportAndEqualOddsRatio', '>=', $supportAndEqualOddsRatio)->where('BaseVolume', '>=', $minimumVolumeOfBaseCurrencyBTC)->orderBy('supportNLastPercentageDifference', 'asc')->get();
            $currentResistancePricesCurrencies = MarketOddsModel::whereRaw('Ask >=resistancePrice')->where('supportAndEqualOddsRatio', '>=', $supportAndEqualOddsRatio)->where('BaseVolume', '>=', $minimumVolumeOfBaseCurrencyBTC)->orderBy('supportNLastPercentageDifference', 'desc')->get();


            $pastSupportPricesCurrencies = PastInvestmentsOpportunitiesModel::where('position', '=', 'open')->orderBy('supportNLastPercentageDifference', 'asc')->get();

            $isPastInvestmentsOpportunitiesModelUpdated = false;
            foreach ($currentSupportPricesCurrencies as $currentSupportPricesCurrency) {
                $isPositionOpen = false;
                foreach ($pastSupportPricesCurrencies as $pastSupportPricesCurrency) {
                    if (strcasecmp($currentSupportPricesCurrency->MarketName, $pastSupportPricesCurrency->MarketName) == 0) {
                        $isPositionOpen = true;
                        break;
                    }
                }
                if (!$isPositionOpen) {
                    $currentSupportPricesCurrency->id = null;
                    $currentSupportPricesCurrency->position = 'open';
//                    $currentSupportPricesCurrency->isSellingOnResistancePaused = 'Yes';
                    $currentSupportPricesCurrency->orderType = 'Buy';
                    $currentSupportPricesCurrency->referenceCode = rand(100000000,999999999);
                    $array = json_decode(json_encode($currentSupportPricesCurrency), TRUE);
                    PastInvestmentsOpportunitiesModel::insert($array);
                    $isPastInvestmentsOpportunitiesModelUpdated = true;
                }
            }


            foreach ($pastSupportPricesCurrencies as $pastSupportPricesCurrency) {
                foreach ($currentResistancePricesCurrencies as $currentResistancePricesCurrency) {
                    if (strcasecmp($pastSupportPricesCurrency->MarketName, $currentResistancePricesCurrency->MarketName) == 0) {
                        if (strcasecmp($pastSupportPricesCurrency->position, 'open') == 0) {
                            $pastSupportPriceData = PastInvestmentsOpportunitiesModel::find($pastSupportPricesCurrency->id);
                            $pastSupportPriceData->position = 'closed';
                            $pastSupportPriceData->save();
                            $pastSupportPricesCurrency->id = null;
                            $pastSupportPricesCurrency->orderType = 'Sell';
                            $pastSupportPricesCurrency->position = 'closed';
                            $array = json_decode(json_encode($pastSupportPricesCurrency), TRUE);
                            PastInvestmentsOpportunitiesModel::insert($array);
                            $isPastInvestmentsOpportunitiesModelUpdated = true;
                            break;
                        }
                    }
                }
            }

            if ($isPastInvestmentsOpportunitiesModelUpdated) {
                $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
                $botRunningStatusData->dbAffected = 'PastInvestmentsOpportunitiesModel';
                $botRunningStatusData->dbLatestUpdatesTimestamp = Carbon::now();
                $botRunningStatusData->save();
            }

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


}