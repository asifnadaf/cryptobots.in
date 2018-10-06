<?php

namespace App\Classes\BittrexKoinexArbitrageUtilities;

use Log;
use App\Models\KoinexTickerDataModel;
use App\Models\KoinexTickerDataVolumeModel;
use App\Classes\BittrexAPIs;

class BittrexKoinexArbitrageUtilities
{

    public function getKoinexMarket()
    {
        try {

            $url = "https://koinex.in/api/ticker";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
            $html = curl_exec($ch);
            curl_close($ch);

            $tickers = json_decode($html, true);
            return $tickers;

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . $exception);
            $tickers = '';
        }
        return $tickers;
    }

    public function getKoinexTickerFromDB()
    {
        $koinexTickerDataFromDB = KoinexTickerDataModel::get();
        return $koinexTickerDataFromDB;

    }

    public function getKoinexTickerVolumeFromDB()
    {
        $koinexTickerVolumeDataFromDB = KoinexTickerDataVolumeModel::get();
        return $koinexTickerVolumeDataFromDB;

    }


    public function getBittrexOrderBook($marketName)
    {
        $bittrexAPIs = new BittrexAPIs(null, null);
        $response = $bittrexAPIs->getOrderBook($marketName, 'both', 10);
        return $response;

    }

}