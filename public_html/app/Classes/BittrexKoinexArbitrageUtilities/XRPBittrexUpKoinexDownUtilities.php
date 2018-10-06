<?php

namespace App\Classes\BittrexKoinexArbitrageUtilities;

use Log;
use Carbon\Carbon;
use App\Models\BittrexKoinexArbitrageSettingsModel;

class XRPBittrexUpKoinexDownUtilities
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

            $bittrexTickerResponse = $bittrexKoinexArbitrageUtilities->getBittrexOrderBook('BTC-XRP');

            $bittrexXRPRate = round($bittrexTickerResponse->buy[0]->Rate, 8);
            $bittrexXRPQuantity = round($bittrexTickerResponse->buy[0]->Quantity, 8);

            $koinexXRPRate = $koinexTickerDataFromDB->XRPAsk;

            if ($bittrexXRPQuantity * $koinexXRPRate > $maximumTradeSize) {
                $bittrexXRPQuantity = $maximumTradeSize / $koinexXRPRate;
            }

            $bittrexBTCQuantity = round($bittrexXRPRate * $bittrexXRPQuantity, 8);

            $koinexXRPQuantity = $bittrexXRPQuantity;

            $koinexBTCRate = $koinexTickerDataFromDB->BTCBid;
            $koinexBTCQuantity = $bittrexBTCQuantity;

            $XRP_BTC_Koinex_Ratio = round($koinexXRPRate / $koinexBTCRate, 8);
            $arbitrageReturns = round(($bittrexXRPRate - $XRP_BTC_Koinex_Ratio) / $XRP_BTC_Koinex_Ratio * 100, 2);

            if ($koinexXRPRate * $koinexXRPQuantity < $minimumTradeSize) {
                $message = 'Minimum trade size criteria is not met.';
                $result['message'] = $message;
                return $result;
            }


            $result['data']['timestamp'] = Carbon::now()->diffInSeconds($koinexTickerDataFromDB->updated_at);
            $result['data']['returns'] = $arbitrageReturns;
            $result['data']['BTC-XRP']['Rate'] = $bittrexXRPRate;
            $result['data']['BTC-XRP']['Quantity'] = $bittrexXRPQuantity;

            $result['data']['INR-XRP']['Rate'] = $koinexXRPRate;
            $result['data']['INR-XRP']['Quantity'] = $koinexXRPQuantity;

            $result['data']['INR-BTC']['Rate'] = $koinexBTCRate;
            $result['data']['INR-BTC']['Quantity'] = $koinexBTCQuantity;

            $instructions = [];

            if ($arbitrageReturns >= $minimumGrossPercentGain) {
                $explanation = [];
                $explanation['firstTransaction'] = ' Sell ' . round($bittrexXRPQuantity, 8) . ' units of XRP @ BTC ' . round($bittrexXRPRate, 8) . ' per unit to get a total of BTC ' . round($bittrexXRPQuantity * $bittrexXRPRate, 8) . ' in Bittrex';
                $explanation['secondTransaction'] = 'Buy ' . round($koinexXRPQuantity, 8) . ' units of XRP @ Rs. ' . round($koinexXRPRate, 2) . ' per unit to spend a total of Rs. ' . round($koinexXRPQuantity * $koinexXRPRate, 2) . ' in Koinex';
                $explanation['thirdTransaction'] = ' Sell ' . round($koinexBTCQuantity, 8) . ' units of BTC @ Rs. ' . round($koinexBTCRate, 2) . ' per unit to get a total of Rs. ' . round($koinexBTCQuantity * $koinexBTCRate, 2) . ' in Koinex';
                $instructions ['XRP_BittrexUp_KoinexDown'] = $explanation;
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