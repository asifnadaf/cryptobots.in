<?php

namespace App\Classes\BittrexKoinexArbitrageUtilities;

use Log;
use Carbon\Carbon;
use App\Models\BittrexKoinexArbitrageSettingsModel;

class LTCBittrexUpKoinexDownUtilities
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

            $bittrexTickerResponse = $bittrexKoinexArbitrageUtilities->getBittrexOrderBook('BTC-LTC');

            $bittrexLTCRate = round($bittrexTickerResponse->buy[0]->Rate, 8);
            $bittrexLTCQuantity = round($bittrexTickerResponse->buy[0]->Quantity, 8);

            $koinexLTCRate = $koinexTickerDataFromDB->LTCAsk;

            if ($bittrexLTCQuantity * $koinexLTCRate > $maximumTradeSize) {
                $bittrexLTCQuantity = $maximumTradeSize / $koinexLTCRate;
            }

            $bittrexBTCQuantity = round($bittrexLTCRate * $bittrexLTCQuantity, 8);

            $koinexLTCQuantity = $bittrexLTCQuantity;

            $koinexBTCRate = $koinexTickerDataFromDB->BTCBid;
            $koinexBTCQuantity = $bittrexBTCQuantity;

            $LTC_BTC_Koinex_Ratio = round($koinexLTCRate / $koinexBTCRate, 8);
            $arbitrageReturns = round(($bittrexLTCRate - $LTC_BTC_Koinex_Ratio) / $LTC_BTC_Koinex_Ratio * 100, 2);

            if ($koinexLTCRate * $koinexLTCQuantity < $minimumTradeSize) {
                $message = 'Minimum trade size criteria is not met.';
                $result['message'] = $message;
                return $result;
            }


            $result['data']['timestamp'] = Carbon::now()->diffInSeconds($koinexTickerDataFromDB->updated_at);
            $result['data']['returns'] = $arbitrageReturns;
            $result['data']['BTC-LTC']['Rate'] = $bittrexLTCRate;
            $result['data']['BTC-LTC']['Quantity'] = $bittrexLTCQuantity;

            $result['data']['INR-LTC']['Rate'] = $koinexLTCRate;
            $result['data']['INR-LTC']['Quantity'] = $koinexLTCQuantity;

            $result['data']['INR-BTC']['Rate'] = $koinexBTCRate;
            $result['data']['INR-BTC']['Quantity'] = $koinexBTCQuantity;

            $instructions = [];

            if ($arbitrageReturns >= $minimumGrossPercentGain) {
                $explanation = [];
                $explanation['firstTransaction'] = ' Sell ' . round($bittrexLTCQuantity, 8) . ' units of LTC @ BTC ' . round($bittrexLTCRate, 8) . ' per unit to get a total of BTC ' . round($bittrexLTCQuantity * $bittrexLTCRate, 8) . ' in Bittrex';
                $explanation['secondTransaction'] = 'Buy ' . round($koinexLTCQuantity, 8) . ' units of LTC @ Rs. ' . round($koinexLTCRate, 2) . ' per unit to spend a total of Rs. ' . round($koinexLTCQuantity * $koinexLTCRate, 2) . ' in Koinex';
                $explanation['thirdTransaction'] = ' Sell ' . round($koinexBTCQuantity, 8) . ' units of BTC @ Rs. ' . round($koinexBTCRate, 2) . ' per unit to get a total of Rs. ' . round($koinexBTCQuantity * $koinexBTCRate, 2) . ' in Koinex';
                $instructions ['LTC_BittrexUp_KoinexDown'] = $explanation;
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