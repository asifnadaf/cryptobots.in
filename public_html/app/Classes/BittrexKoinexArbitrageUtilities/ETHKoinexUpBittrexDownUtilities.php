<?php

namespace App\Classes\BittrexKoinexArbitrageUtilities;

use Log;
use Carbon\Carbon;
use App\Models\BittrexKoinexArbitrageSettingsModel;

class ETHKoinexUpBittrexDownUtilities
{

    public function findOpportunity()
    {
        $result = [];

        $settings = BittrexKoinexArbitrageSettingsModel::first();
        $minimumTradeSize = $settings->minimumTradeSize;
        $maximumTradeSize = $settings->maximumTradeSize;
        $minimumGrossPercentGain = $settings->minimumGrossPercentGain;
        $bittrexKoinexArbitrageUtilities = new BittrexKoinexArbitrageUtilities();
        $koinexTickerDataFromDB = $bittrexKoinexArbitrageUtilities->getKoinexTickerFromDB();

        $lastRun = $koinexTickerDataFromDB->updated_at;
        $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
        $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

        $fifteenSecondsBack = Carbon::now()->subSeconds(25);
        $timeDifference = $fifteenSecondsBack->diffInSeconds($lastRun, false);

        if ($timeDifference < 0) {
            $message = 'Koinex ticker data is not latest. Ticker data request timestamp is '.  Carbon::now()->format('d-m-Y G:i:s').' . Last updated timestamp is ' . $koinexTickerDataFromDB->updated_at->format('d-m-Y G:i:s');
            $result['message'] = $message;
            return $result;
        }

        if (count($koinexTickerDataFromDB) > 0) {

            $bittrexTickerResponse = $bittrexKoinexArbitrageUtilities->getBittrexOrderBook('BTC-ETH');

            $bittrexETHRate = round($bittrexTickerResponse->sell[0]->Rate, 8);
            $bittrexETHQuantity = round($bittrexTickerResponse->sell[0]->Quantity, 8);

            $koinexETHRate = $koinexTickerDataFromDB->ETHBid;

            if ($bittrexETHQuantity * $koinexETHRate > $maximumTradeSize) {
                $bittrexETHQuantity = $maximumTradeSize / $koinexETHRate;
            }

            $bittrexBTCQuantity = round($bittrexETHRate * $bittrexETHQuantity, 8);

            $koinexETHQuantity = $bittrexETHQuantity;

            $koinexBTCRate = $koinexTickerDataFromDB->BTCBid;
            $koinexBTCQuantity = $bittrexBTCQuantity;

            $ETH_BTC_Koinex_Ratio = round($koinexETHRate / $koinexBTCRate, 8);
            $arbitrageReturns = round(($ETH_BTC_Koinex_Ratio - $bittrexETHRate) / $bittrexETHRate * 100, 2);

            if ($koinexETHRate * $koinexETHQuantity < $minimumTradeSize) {
                $message = 'Minimum trade size criteria is not met.';
                $result['message'] = $message;
                return $result;
            }


            $result['data']['timestamp'] = Carbon::now()->diffInSeconds($koinexTickerDataFromDB->updated_at);
            $result['data']['returns'] = $arbitrageReturns;
            $result['data']['BTC-ETH']['Rate'] = $bittrexETHRate;
            $result['data']['BTC-ETH']['Quantity'] = $bittrexETHQuantity;

            $result['data']['INR-ETH']['Rate'] = $koinexETHRate;
            $result['data']['INR-ETH']['Quantity'] = $koinexETHQuantity;

            $result['data']['INR-BTC']['Rate'] = $koinexBTCRate;
            $result['data']['INR-BTC']['Quantity'] = $koinexBTCQuantity;

            $instructions = [];

            if ($arbitrageReturns >= $minimumGrossPercentGain) {
                $explanation = [];
                $explanation['firstTransaction'] = ' Sell ' . round($koinexETHQuantity, 8) . ' units of ETH @ Rs ' . round($koinexETHRate, 8) . ' per unit to get a total of Rs ' . round($koinexETHQuantity * $koinexETHRate, 8) . ' in Koinex';
                $explanation['secondTransaction'] = 'Buy ' . round($bittrexETHQuantity, 8) . ' units of ETH @ BTC ' . round($bittrexETHRate, 8) . ' per unit to spend a total of BTC ' . round($bittrexETHQuantity * $bittrexETHRate, 8) . ' in Bittrex';
                $explanation['thirdTransaction'] = 'Buy ' . round($koinexBTCQuantity, 8) . ' units of BTC @ Rs. ' . round($koinexBTCRate, 2) . ' per unit to spend a total of Rs . ' . round($koinexBTCQuantity * $koinexBTCRate, 2) . ' in Koinex';
                $instructions ['ETH_KoinexUp_BittrexDown'] = $explanation;
            }

            $result['instructions'] = $instructions;
            $message = '';
            $result['message'] = $message;
            return $result;

        } else {
            $message = 'DB is not sending Koinex ticker data, please try after sometime';
            $result['message'] = $message;
            return $result;
        }

    }

}