<?php

namespace App\Classes\BittrexKoinexArbitrageBots;

use App\Classes\BittrexKoinexArbitrageUtilities\BittrexKoinexArbitrageUtilities;

use App\Models\KoinexTickerDataModel;
use App\Models\BotRunningStatusModel;
use Log;
use Carbon\Carbon;

class RecordKoinexTickersBot
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
            $bittrexKoinexArbitrageUtilities = new BittrexKoinexArbitrageUtilities();
            $koinexTickerResponse = $bittrexKoinexArbitrageUtilities->getKoinexMarket();

            if (count($koinexTickerResponse) > 0) {
                $this->record($koinexTickerResponse);
                $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
                $botRunningStatusData->runsEvery = 'Every minute';
                $botRunningStatusData->lastRun = Carbon::now();
                $botRunningStatusData->save();
            }

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

        $endTime = Carbon::now();
        $differenceBetweenStartTimeAndEndTime = $startTime->diffInSeconds($endTime, false);
        Log::info(get_class($this) . '->' . __FUNCTION__ . ' running time: ' . ' start time ' . $startTime . ' end time ' . $endTime . ' $differenceBetweenStartTimeAndEndTime ' . $differenceBetweenStartTimeAndEndTime);

    }

    public function record($koinexTickerResponse)
    {

        $currencyName = 'BTC';
        $rowToBeUpdated = KoinexTickerDataModel::where('currencyName', '=', $currencyName)->first();

        $rowToBeUpdated->currencyName = $currencyName;
        $rowToBeUpdated->bid = $koinexTickerResponse['stats']['BTC']['highest_bid'];
        $rowToBeUpdated->ask = $koinexTickerResponse['stats']['BTC']['lowest_ask'];
        $rowToBeUpdated->maxPrice24Hours = $koinexTickerResponse['stats']['BTC']['max_24hrs'];
        $rowToBeUpdated->minPrice24Hours = $koinexTickerResponse['stats']['BTC']['min_24hrs'];
        $rowToBeUpdated->updated_at = Carbon::now();
        $rowToBeUpdated->save();

        $currencyName = 'BCC';
        $rowToBeUpdated = KoinexTickerDataModel::where('currencyName', '=', $currencyName)->first();

        $rowToBeUpdated->currencyName = $currencyName;
        $rowToBeUpdated->bid = $koinexTickerResponse['stats']['BCH']['highest_bid'];
        $rowToBeUpdated->ask = $koinexTickerResponse['stats']['BCH']['lowest_ask'];
        $rowToBeUpdated->maxPrice24Hours = $koinexTickerResponse['stats']['BCH']['max_24hrs'];
        $rowToBeUpdated->minPrice24Hours = $koinexTickerResponse['stats']['BCH']['min_24hrs'];
        $rowToBeUpdated->updated_at = Carbon::now();
        $rowToBeUpdated->save();

        $currencyName = 'ETH';
        $rowToBeUpdated = KoinexTickerDataModel::where('currencyName', '=', $currencyName)->first();

        $rowToBeUpdated->currencyName = $currencyName;
        $rowToBeUpdated->bid = $koinexTickerResponse['stats']['ETH']['highest_bid'];
        $rowToBeUpdated->ask = $koinexTickerResponse['stats']['ETH']['lowest_ask'];
        $rowToBeUpdated->maxPrice24Hours = $koinexTickerResponse['stats']['ETH']['max_24hrs'];
        $rowToBeUpdated->minPrice24Hours = $koinexTickerResponse['stats']['ETH']['min_24hrs'];
        $rowToBeUpdated->updated_at = Carbon::now();
        $rowToBeUpdated->save();

        $currencyName = 'LTC';
        $rowToBeUpdated = KoinexTickerDataModel::where('currencyName', '=', $currencyName)->first();

        $rowToBeUpdated->currencyName = $currencyName;
        $rowToBeUpdated->bid = $koinexTickerResponse['stats']['LTC']['highest_bid'];
        $rowToBeUpdated->ask = $koinexTickerResponse['stats']['LTC']['lowest_ask'];
        $rowToBeUpdated->maxPrice24Hours = $koinexTickerResponse['stats']['LTC']['max_24hrs'];
        $rowToBeUpdated->minPrice24Hours = $koinexTickerResponse['stats']['LTC']['min_24hrs'];
        $rowToBeUpdated->updated_at = Carbon::now();
        $rowToBeUpdated->save();

        $currencyName = 'XRP';
        $rowToBeUpdated = KoinexTickerDataModel::where('currencyName', '=', $currencyName)->first();

        $rowToBeUpdated->currencyName = $currencyName;
        $rowToBeUpdated->bid = $koinexTickerResponse['stats']['XRP']['highest_bid'];
        $rowToBeUpdated->ask = $koinexTickerResponse['stats']['XRP']['lowest_ask'];
        $rowToBeUpdated->maxPrice24Hours = $koinexTickerResponse['stats']['XRP']['max_24hrs'];
        $rowToBeUpdated->minPrice24Hours = $koinexTickerResponse['stats']['XRP']['min_24hrs'];
        $rowToBeUpdated->updated_at = Carbon::now();
        $rowToBeUpdated->save();

        $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
        $botRunningStatusData->dbAffected = 'KoinexTickerDataModel';
        $botRunningStatusData->dbLatestUpdatesTimestamp = Carbon::now();
        $botRunningStatusData->save();

    }

}