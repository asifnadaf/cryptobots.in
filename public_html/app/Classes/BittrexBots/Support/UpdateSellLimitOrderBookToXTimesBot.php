<?php

namespace App\Classes\BittrexBots\Support;

use App\Models\MarketOddsModel;
use App\Models\BotSettingsModel;
use App\Models\ClientsListModel;
use App\Models\BotRunningStatusModel;
use App\Classes\BittrexAccountUtilities;
use App\Classes\BittrexGeneralUtilities;
use App\Classes\BittrexMarketUtilities;
use App\Classes\BittrexAPIs;
use Log;
use Carbon\Carbon;


class UpdateSellLimitOrderBookToXTimesBot
{
    var $BTCPrefix = 'BTC-';
    var $className = null;

    public function __construct()
    {
        $this->className = (new \ReflectionClass($this))->getShortName();
    }

    public function updateSellLimitOrderBookToAutoXTime()
    {

        try {

            $startTime = Carbon::now();

            $this->cancelAllSellLimit();
            sleep(1);
            $this->createAllSellLimit();

            $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
            $botRunningStatusData->lastRun = Carbon::now();
            $botRunningStatusData->runsEvery = 'Every 30 minutes';
            $botRunningStatusData->dbAffected = 'None';
            $botRunningStatusData->save();

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

        $endTime = Carbon::now();
        $differenceBetweenStartTimeAndEndTime = $startTime->diffInSeconds($endTime, false);
        Log::info(get_class($this) . '->' . __FUNCTION__ . ' running time: ' . ' start time ' . $startTime . ' end time ' . $endTime . ' $differenceBetweenStartTimeAndEndTime ' . $differenceBetweenStartTimeAndEndTime);

    }


    public function isSellingAt2XPaused($marketName)
    {
        $isSellingAt2XPaused = false;
        $altcoinData = MarketOddsModel::where('MarketName', '=', $marketName)->first();
        if (count($altcoinData) > 0) {
            if (strcasecmp($altcoinData->isSellingAt2XPaused, 'Yes') == 0) {
                $isSellingAt2XPaused = true;
            }
        }
        return $isSellingAt2XPaused;
    }


    public function cancelAllSellLimit()
    {
        try {

            $clientsList = ClientsListModel::all();
            foreach ($clientsList as $row) {
                $apiKey = $row->apiKey;
                $secretKey = $row->secretKey;

                $pauseTrading = $row->pauseTrading;
                if (strcasecmp($pauseTrading, 'Yes') == 0) {
                    continue;
                }

                $bittrexUtilities = new BittrexMarketUtilities($apiKey, $secretKey);
                $openOrders = $bittrexUtilities->getOpenOrders(null);

                foreach ($openOrders as $openOrder) {
                    if (strcasecmp($openOrder->OrderType, 'LIMIT_SELL') == 0) {
                        try {

                            $isSellingAt2XPaused = $this->isSellingAt2XPaused($openOrder->Exchange);
                            if ($isSellingAt2XPaused) {
                                continue;
                            }

                            $orderUuid = $openOrder->OrderUuid;
                            $bittrexUtilities->cancel($orderUuid);

                        } catch (Exception $exception) {
                            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'Market Name ' . $openOrder->Exchange);
                            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception ' . $exception);
                        }
                    }
                }

            }

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' General exception ' . $exception);
        }

    }

    public function createAllSellLimit()
    {
        $botSettings = BotSettingsModel::first();
        $timesFactor = $botSettings->pumpFactor;

        $bittrexGeneralUtilities = new BittrexGeneralUtilities(null, null);
        $randomFactor = $bittrexGeneralUtilities->float_rand(1.0001, 1.0015, 4); // Bid any random number between 0.0025% and 0.05% then the going ask rate at the exchange.

        $timesFactor = $timesFactor * $randomFactor;
        $bittrexMinimumInvestmentSize = 0.00050000;             // Minimum investment amount restrictions from exchange.

        try {

            $clientsList = ClientsListModel::all();

            foreach ($clientsList as $row) {
                $apiKey = $row->apiKey;
                $secretKey = $row->secretKey;

                $pauseTrading = $row->pauseTrading;
                if (strcasecmp($pauseTrading, 'Yes') == 0) {
                    continue;
                }

                $bittrexAccountUtilities = new BittrexAccountUtilities($apiKey, $secretKey);
                $altcoinsBalance = $bittrexAccountUtilities->getAltcoinsBalance();

                $bittrexAPIs = new BittrexAPIs($apiKey, $secretKey);

                foreach ($altcoinsBalance as $balance) {
                    try {
                        $marketName = $this->BTCPrefix . $balance->Currency;

                        $isSellingAt2XPaused = $this->isSellingAt2XPaused($marketName);
                        if ($isSellingAt2XPaused) {
                            continue;
                        }

                        $bittrexMarketUtilities = new BittrexMarketUtilities($apiKey, $secretKey);
                        $ticker = $bittrexMarketUtilities->getTickerDataFromDB($marketName);
                        $quantity = $balance->Available;
                        $bidRate = $ticker->Bid;
                        if ($quantity * $bidRate >= $bittrexMinimumInvestmentSize) {
                            $rate = $bidRate * $timesFactor;
                            $sellLimitResponse['status'] = $bittrexAPIs->sellLimit($marketName, $quantity, (float)$rate);
                        } else {
                            Log::info(get_class($this) . '->' . __FUNCTION__ . ' Sell limit order is not placed because it does not meet minimum investments requirement of exchange $marketName ' . $marketName . ' $quantity ' . $quantity . ' $bidRate ' . $bidRate . ' $quantity * $bidRate ' . $quantity * $bidRate);
                        }

                    } catch (Exception $exception) {
                        Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'Market Name ' . $marketName . ' $quantity ' . $quantity . ' $rate ' . $rate);
                        Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception ' . $exception);
                    }
                }

            }

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' General exception: ' . '   exception ' . $exception);
        }
    }


}