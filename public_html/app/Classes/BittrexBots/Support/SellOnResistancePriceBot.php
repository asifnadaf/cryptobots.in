<?php

namespace App\Classes\BittrexBots\Support;

use App\Models\MarketOddsModel;
use App\Models\BotRunningStatusModel;
use App\Models\ClientsListModel;
use App\Classes\BittrexAPIs;

use Log;
use Mail;
use Carbon\Carbon;
use Mockery\Exception;

class SellOnResistancePriceBot
{

    var $className = null;
    var $bittrexMinimumInvestmentLimits = 0.00050000;

    public function __construct()
    {
        $this->className = (new \ReflectionClass($this))->getShortName();
    }

    public function sellAboveResistancePrice()
    {
        try {

            $startTime = Carbon::now();

            $aboveResistancePriceList = $this->getAboveResistancePriceList();

            $clientsList = ClientsListModel::all();
            if (count($aboveResistancePriceList) > 0) {
                foreach ($aboveResistancePriceList as $aboveResistancePriceData) {
                    foreach ($clientsList as $client) {
                        $this->sell($client, $aboveResistancePriceData);
                    }
                }
            }

            $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
            $botRunningStatusData->lastRun = Carbon::now();
            $botRunningStatusData->runsEvery = 'Every 10 minutes';
            $botRunningStatusData->save();

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

        $endTime = Carbon::now();
        $differenceBetweenStartTimeAndEndTime = $startTime->diffInSeconds($endTime, false);
        Log::info(get_class($this) . '->' . __FUNCTION__ . ' running time: ' . ' start time ' . $startTime . ' end time ' . $endTime . ' $differenceBetweenStartTimeAndEndTime ' . $differenceBetweenStartTimeAndEndTime);

        return json_encode($aboveResistancePriceList);
    }

    public function getAboveResistancePriceList()
    {
        try {
            $resistancePriceList = MarketOddsModel::whereRaw('Bid >=resistancePrice')->where('isSellingOnResistancePaused', '=', 'No')->where('MarketName', '!=', 'USDT-BTC')->orderBy('supportNLastPercentageDifference', 'desc')->get();
        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
        return $resistancePriceList;
    }


    public function sell($client, $aboveResistancePriceData)
    {

        try {
            $pauseTrading = $client->pauseTrading;
            if (strcasecmp($pauseTrading, 'Yes') == 0) {
                return;
            }

            $bittrexAPIs = new BittrexAPIs($client->apiKey, $client->secretKey);

            $marketName = $aboveResistancePriceData->MarketName;
            $rate = $aboveResistancePriceData->Bid;

            $altcoinsName = str_replace("BTC-", "", $marketName);
            $alcoinBalance = $bittrexAPIs->getBalance($altcoinsName);

            if (count($alcoinBalance) <= 0) {
                return;
            }

            $quantity = $alcoinBalance->Balance;

            if ($quantity <= 0) {
                return;
            }

            if ($quantity * $rate >= $this->bittrexMinimumInvestmentLimits) {
                $openOrder = $bittrexAPIs->getOpenOrders($marketName);
                $orderUuid = $openOrder[0]->OrderUuid;
                if ($orderUuid != null) {
                    $bittrexAPIs->cancel($orderUuid);
                    sleep(1);
                    $bittrexAPIs->sellLimit($marketName, $quantity, $rate);
                    $this->createLogRecordsOfSelling($client, $marketName, $quantity, $rate);
                } else {
                    Log::info(get_class($this) . '->' . __FUNCTION__ . ' $orderUuid is null $marketName ' . $marketName . ' $quantity ' . $quantity . ' rate ' . $rate . ' $orderUuid ' . $orderUuid);
                }

            } else {
                Log::info(get_class($this) . '->' . __FUNCTION__ . ' Sell limit order is not placed because it does not meet minimum investments requirement of exchange $client' . $client->fullName . ' $marketName ' . $marketName . ' $quantity ' . $quantity . ' rate ' . $rate);
            }

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

    }


    public function createLogRecordsOfSelling($clientData, $marketName, $quantity, $rate)
    {

        try {
            foreach ($clientData as $row) {
                Log::info(get_class($this) . '->' . __FUNCTION__);
                Log::info('SELL ABVOVE RESISTANCE PRICE FOR ALL CLIENTS DETAILS');
                Log::info(get_class($this) . '->' . __FUNCTION__ . 'Client name ' . $clientData->fullName);
                Log::info(get_class($this) . '->' . __FUNCTION__ . 'MarketName ' . $marketName);
                Log::info(get_class($this) . '->' . __FUNCTION__ . 'quantity ' . $quantity);
                Log::info(get_class($this) . '->' . __FUNCTION__ . 'rate ' . $rate);
                Log::info('');
            }

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

    }


}