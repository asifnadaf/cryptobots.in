<?php

namespace App\Classes;

use Log;
use App\Models\ClientsListModel;
use LaravelAcl\Authentication\Classes\SentryAuthenticator;

class BittrexUSDTMarketUtilities
{
    var $bittrexMinimumInvestmentLimits = 0.00050000;

    public function buyBTCUsingTether()
    {

        $marketName = 'USDT-BTC';
        $clientsList = ClientsListModel::all();
        foreach ($clientsList as $clientData) {

            $apiKey = $clientData->apiKey;
            $secretKey = $clientData->secretKey;

            $pauseTrading = $clientData->pauseTrading;
            if (strcasecmp($pauseTrading, 'Yes') == 0) {
                continue;
            }

            $bittrexAPIs = new BittrexAPIs($apiKey, $secretKey);

            $tetherBalance = $bittrexAPIs->getBalance('USDT');
            $ticker = $bittrexAPIs->getTicker($marketName);

            $rate = $ticker->Ask;
            $quantity = $tetherBalance->Balance/$rate*0.99;

            if ($quantity >= $this->bittrexMinimumInvestmentLimits) {
                Log::info(get_class($this) . '->' . __FUNCTION__ . ' Market Name ' . $marketName . ' $quantity ' . $quantity . ' $rate ' . $rate);
                $openOrders = $bittrexAPIs->getOpenOrders($marketName);

                if (count($openOrders)) {
                    foreach ($openOrders as $openOrder)
                        $orderUuid = $openOrder->OrderUuid;
                    if ($orderUuid != null) {
                        $bittrexAPIs->cancel($orderUuid);
                        sleep(1);
                    }
                }

                $bittrexAPIs->buyLimit($marketName, $quantity, $rate);
                $this->createLogRecordsOfBuying($clientData, $marketName, $quantity, $rate);

            } else {
                Log::info(get_class($this) . '->' . __FUNCTION__ . ' Sell limit order is not placed because it does not meet minimum investments requirement of exchange $marketName ' . $marketName . ' $quantity ' . $quantity . ' rate ' . $rate);
            }

        }

    }


    public function createLogRecordsOfBuying($clientData, $marketName, $quantity, $rate)
    {

        try {
            Log::info('');
            Log::info(get_class($this) . '->' . __FUNCTION__);
            Log::info('USDT MARKET - Buy BTC / Sell Tether');
            Log::info(get_class($this) . '->' . __FUNCTION__ . 'Client name ' . $clientData->fullName);
            Log::info(get_class($this) . '->' . __FUNCTION__ . 'MarketName ' . $marketName);
            Log::info(get_class($this) . '->' . __FUNCTION__ . 'rate ' . $rate);
            Log::info(get_class($this) . '->' . __FUNCTION__ . 'quantity ' . $quantity);
            Log::info('');

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

    }


    public function createLogRecordsOfSelling($clientData, $marketName, $quantity, $rate)
    {

        try {
            foreach ($clientData as $row) {
                Log::info(get_class($this) . '->' . __FUNCTION__);
                Log::info('USDT MARKET - Sell BTC / Buy Tether');
                Log::info(get_class($this) . '->' . __FUNCTION__ . 'Client name ' . $row->fullName);
                Log::info(get_class($this) . '->' . __FUNCTION__ . 'MarketName ' . $marketName);
                Log::info(get_class($this) . '->' . __FUNCTION__ . 'rate ' . $rate);
                Log::info(get_class($this) . '->' . __FUNCTION__ . 'quantity ' . $quantity);
                Log::info('');
            }

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

    }



}