<?php

namespace App\Classes\BittrexKoinexArbitrageUtilities;

use Log;
use Carbon\Carbon;
use App\Models\BittrexKoinexArbitrageSettingsModel;

class BCCKoinexUpBittrexDownUtilities
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

            $bittrexTickerResponse = $bittrexKoinexArbitrageUtilities->getBittrexOrderBook('BTC-BCC');

            $bittrexBCCRate = round($bittrexTickerResponse->sell[0]->Rate, 8);
            $bittrexBCCQuantity = round($bittrexTickerResponse->sell[0]->Quantity, 8);

            $koinexBCCRate = $koinexTickerDataFromDB->BCCBid;

            if ($bittrexBCCQuantity * $koinexBCCRate > $maximumTradeSize) {
                $bittrexBCCQuantity = $maximumTradeSize / $koinexBCCRate;
            }

            $bittrexBTCQuantity = round($bittrexBCCRate * $bittrexBCCQuantity, 8);

            $koinexBCCQuantity = $bittrexBCCQuantity;

            $koinexBTCRate = $koinexTickerDataFromDB->BTCBid;
            $koinexBTCQuantity = $bittrexBTCQuantity;

            $BCC_BTC_Koinex_Ratio = round($koinexBCCRate / $koinexBTCRate, 8);
            $arbitrageReturns = round(($BCC_BTC_Koinex_Ratio - $bittrexBCCRate) / $bittrexBCCRate * 100, 2);

            if ($koinexBCCRate * $koinexBCCQuantity < $minimumTradeSize) {
                $message = 'Minimum trade size criteria is not met.';
                $result['message'] = $message;
                return $result;
            }


            $result['data']['timestamp'] = Carbon::now()->diffInSeconds($koinexTickerDataFromDB->updated_at);
            $result['data']['returns'] = $arbitrageReturns;
            $result['data']['BTC-BCC']['Rate'] = $bittrexBCCRate;
            $result['data']['BTC-BCC']['Quantity'] = $bittrexBCCQuantity;

            $result['data']['INR-BCC']['Rate'] = $koinexBCCRate;
            $result['data']['INR-BCC']['Quantity'] = $koinexBCCQuantity;

            $result['data']['INR-BTC']['Rate'] = $koinexBTCRate;
            $result['data']['INR-BTC']['Quantity'] = $koinexBTCQuantity;

            $instructions = [];

            if ($arbitrageReturns >= $minimumGrossPercentGain) {
                $explanation = [];
                $explanation['firstTransaction'] = ' Sell ' . round($koinexBCCQuantity, 8) . ' units of BCC @ Rs ' . round($koinexBCCRate, 8) . ' per unit to get a total of Rs ' . round($koinexBCCQuantity * $koinexBCCRate, 8) . ' in Koinex';
                $explanation['secondTransaction'] = 'Buy ' . round($bittrexBCCQuantity, 8) . ' units of BCC @ BTC ' . round($bittrexBCCRate, 8) . ' per unit to spend a total of BTC ' . round($bittrexBCCQuantity * $bittrexBCCRate, 8) . ' in Bittrex';
                $explanation['thirdTransaction'] = 'Buy ' . round($koinexBTCQuantity, 8) . ' units of BTC @ Rs. ' . round($koinexBTCRate, 2) . ' per unit to spend a total of Rs . ' . round($koinexBTCQuantity * $koinexBTCRate, 2) . ' in Koinex';
                $instructions ['BCC_KoinexUp_BittrexDown'] = $explanation;
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