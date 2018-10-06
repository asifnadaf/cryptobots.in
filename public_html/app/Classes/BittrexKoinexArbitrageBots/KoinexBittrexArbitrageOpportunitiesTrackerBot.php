<?php

namespace App\Classes\BittrexKoinexArbitrageBots;

use App\Classes\BittrexKoinexArbitrageUtilities\ArbitrageOverviewUtilities;

use App\Models\KoinexBittrexArbitrageOpportunitiesTrackerModel;
use App\Models\BotRunningStatusModel;
use Log;
use Carbon\Carbon;

class KoinexBittrexArbitrageOpportunitiesTrackerBot
{
    var $className = null;

    public function __construct()
    {
        $this->className = (new \ReflectionClass($this))->getShortName();
    }

    public function runBot()
    {
        $startTime = Carbon::now();
        try {

            $this->record();
            $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
            $botRunningStatusData->runsEvery = 'Every minute';
            $botRunningStatusData->lastRun = Carbon::now();
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

        $arbitrageOverviewUtilities = new ArbitrageOverviewUtilities();
        $result = $arbitrageOverviewUtilities->findOpportunities();
        $isDBTableAffected = false;

        if ($result['BCCBittrexUpKoinexDown']['isArbitrageOpportunity'] == true) {
            $opportunityType = 'BCCBittrexUpKoinexDown';
            $grossPercentGain = $result['BCCBittrexUpKoinexDown']['data']['returns'];
            $grossGainInINR = $result['BCCBittrexUpKoinexDown']['data']['buyTransactionValue'] * $grossPercentGain / 100;

            $newOpportunity = new KoinexBittrexArbitrageOpportunitiesTrackerModel();
            $newOpportunity->opportunityType = $opportunityType;
            $newOpportunity->buyTransactionValue =  $result['BCCBittrexUpKoinexDown']['data']['buyTransactionValue'];
            $newOpportunity->sellTransactionValue =  $result['BCCBittrexUpKoinexDown']['data']['sellTransactionValue'];
            $newOpportunity->grossPercentGain = $grossPercentGain;
            $newOpportunity->grossGainInINR = $grossGainInINR;
            $newOpportunity->created_at = Carbon::now();
            $newOpportunity->updated_at = Carbon::now();
            $newOpportunity->save();
            $isDBTableAffected = true;
        }


        if ($result['ETHBittrexUpKoinexDown']['isArbitrageOpportunity'] == true) {
            $opportunityType = 'ETHBittrexUpKoinexDown';
            $grossPercentGain = $result['ETHBittrexUpKoinexDown']['data']['returns'];
            $grossGainInINR = $result['ETHBittrexUpKoinexDown']['data']['buyTransactionValue'] * $grossPercentGain / 100;

            $newOpportunity = new KoinexBittrexArbitrageOpportunitiesTrackerModel();
            $newOpportunity->opportunityType = $opportunityType;
            $newOpportunity->buyTransactionValue =  $result['ETHBittrexUpKoinexDown']['data']['buyTransactionValue'];
            $newOpportunity->sellTransactionValue =  $result['ETHBittrexUpKoinexDown']['data']['sellTransactionValue'];
            $newOpportunity->grossPercentGain = $grossPercentGain;
            $newOpportunity->grossGainInINR = $grossGainInINR;
            $newOpportunity->created_at = Carbon::now();
            $newOpportunity->updated_at = Carbon::now();
            $newOpportunity->save();
            $isDBTableAffected = true;
        }


        if ($result['LTCBittrexUpKoinexDown']['isArbitrageOpportunity'] == true) {
            $opportunityType = 'LTCBittrexUpKoinexDown';
            $grossPercentGain = $result['LTCBittrexUpKoinexDown']['data']['returns'];
            $grossGainInINR = $result['LTCBittrexUpKoinexDown']['data']['buyTransactionValue'] * $grossPercentGain / 100;

            $newOpportunity = new KoinexBittrexArbitrageOpportunitiesTrackerModel();
            $newOpportunity->opportunityType = $opportunityType;
            $newOpportunity->buyTransactionValue =  $result['LTCBittrexUpKoinexDown']['data']['buyTransactionValue'];
            $newOpportunity->sellTransactionValue =  $result['LTCBittrexUpKoinexDown']['data']['sellTransactionValue'];
            $newOpportunity->grossPercentGain = $grossPercentGain;
            $newOpportunity->grossGainInINR = $grossGainInINR;
            $newOpportunity->created_at = Carbon::now();
            $newOpportunity->updated_at = Carbon::now();
            $newOpportunity->save();
            $isDBTableAffected = true;
        }


        if ($result['XRPBittrexUpKoinexDown']['isArbitrageOpportunity'] == true) {
            $opportunityType = 'XRPBittrexUpKoinexDown';
            $grossPercentGain = $result['XRPBittrexUpKoinexDown']['data']['returns'];
            $grossGainInINR = $result['XRPBittrexUpKoinexDown']['data']['buyTransactionValue'] * $grossPercentGain / 100;

            $newOpportunity = new KoinexBittrexArbitrageOpportunitiesTrackerModel();
            $newOpportunity->opportunityType = $opportunityType;
            $newOpportunity->buyTransactionValue =  $result['XRPBittrexUpKoinexDown']['data']['buyTransactionValue'];
            $newOpportunity->sellTransactionValue =  $result['XRPBittrexUpKoinexDown']['data']['sellTransactionValue'];
            $newOpportunity->grossPercentGain = $grossPercentGain;
            $newOpportunity->grossGainInINR = $grossGainInINR;
            $newOpportunity->created_at = Carbon::now();
            $newOpportunity->updated_at = Carbon::now();
            $newOpportunity->save();
            $isDBTableAffected = true;
        }


        if ($result['BCCKoinexUpBittrexDown']['isArbitrageOpportunity'] == true) {
            $opportunityType = 'BCCKoinexUpBittrexDown';
            $grossPercentGain = $result['BCCKoinexUpBittrexDown']['data']['returns'];
            $grossGainInINR = $result['BCCKoinexUpBittrexDown']['data']['buyTransactionValue'] * $grossPercentGain / 100;

            $newOpportunity = new KoinexBittrexArbitrageOpportunitiesTrackerModel();
            $newOpportunity->opportunityType = $opportunityType;
            $newOpportunity->buyTransactionValue =  $result['BCCKoinexUpBittrexDown']['data']['buyTransactionValue'];
            $newOpportunity->sellTransactionValue =  $result['BCCKoinexUpBittrexDown']['data']['sellTransactionValue'];
            $newOpportunity->grossPercentGain = $grossPercentGain;
            $newOpportunity->grossGainInINR = $grossGainInINR;
            $newOpportunity->created_at = Carbon::now();
            $newOpportunity->updated_at = Carbon::now();
            $newOpportunity->save();
            $isDBTableAffected = true;
        }


        if ($result['ETHKoinexUpBittrexDown']['isArbitrageOpportunity'] == true) {
            $opportunityType = 'ETHKoinexUpBittrexDown';
            $grossPercentGain = $result['ETHKoinexUpBittrexDown']['data']['returns'];
            $grossGainInINR = $result['ETHKoinexUpBittrexDown']['data']['buyTransactionValue']* $grossPercentGain / 100;

            $newOpportunity = new KoinexBittrexArbitrageOpportunitiesTrackerModel();
            $newOpportunity->opportunityType = $opportunityType;
            $newOpportunity->buyTransactionValue =  $result['ETHKoinexUpBittrexDown']['data']['buyTransactionValue'];
            $newOpportunity->sellTransactionValue =  $result['ETHKoinexUpBittrexDown']['data']['sellTransactionValue'];
            $newOpportunity->grossPercentGain = $grossPercentGain;
            $newOpportunity->grossGainInINR = $grossGainInINR;
            $newOpportunity->created_at = Carbon::now();
            $newOpportunity->updated_at = Carbon::now();
            $newOpportunity->save();
            $isDBTableAffected = true;
        }


        if ($result['LTCKoinexUpBittrexDown']['isArbitrageOpportunity'] == true) {
            $opportunityType = 'LTCKoinexUpBittrexDown';
            $grossPercentGain = $result['LTCKoinexUpBittrexDown']['data']['returns'];
            $grossGainInINR = $result['LTCKoinexUpBittrexDown']['data']['buyTransactionValue'] * $grossPercentGain / 100;

            $newOpportunity = new KoinexBittrexArbitrageOpportunitiesTrackerModel();
            $newOpportunity->opportunityType = $opportunityType;
            $newOpportunity->buyTransactionValue =  $result['LTCKoinexUpBittrexDown']['data']['buyTransactionValue'];
            $newOpportunity->sellTransactionValue =  $result['LTCKoinexUpBittrexDown']['data']['sellTransactionValue'];
            $newOpportunity->grossPercentGain = $grossPercentGain;
            $newOpportunity->grossGainInINR = $grossGainInINR;
            $newOpportunity->created_at = Carbon::now();
            $newOpportunity->updated_at = Carbon::now();
            $newOpportunity->save();
            $isDBTableAffected = true;
        }


        if ($result['XRPKoinexUpBittrexDown']['isArbitrageOpportunity'] == true) {
            $opportunityType = 'XRPKoinexUpBittrexDown';
            $grossPercentGain = $result['XRPKoinexUpBittrexDown']['data']['returns'];
            $grossGainInINR = $result['XRPKoinexUpBittrexDown']['data']['buyTransactionValue'] * $grossPercentGain / 100;

            $newOpportunity = new KoinexBittrexArbitrageOpportunitiesTrackerModel();
            $newOpportunity->opportunityType = $opportunityType;
            $newOpportunity->buyTransactionValue =  $result['XRPKoinexUpBittrexDown']['data']['buyTransactionValue'];
            $newOpportunity->sellTransactionValue =  $result['XRPKoinexUpBittrexDown']['data']['sellTransactionValue'];
            $newOpportunity->grossPercentGain = $grossPercentGain;
            $newOpportunity->grossGainInINR = $grossGainInINR;
            $newOpportunity->created_at = Carbon::now();
            $newOpportunity->updated_at = Carbon::now();
            $newOpportunity->save();
            $isDBTableAffected = true;
        }

        if ($isDBTableAffected) {
            $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
            $botRunningStatusData->dbAffected = 'KoinexBittrexArbitrageOpportunitiesTrackerModel';
            $botRunningStatusData->dbLatestUpdatesTimestamp = Carbon::now();
            $botRunningStatusData->save();
        }


    }


}