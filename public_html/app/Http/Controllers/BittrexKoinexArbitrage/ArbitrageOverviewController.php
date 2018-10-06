<?php

namespace App\Http\Controllers\BittrexKoinexArbitrage;

use App\Http\Controllers\Controller;
use App\Models\KoinexTickerDataModel;
use Log;
use Mail;
use Charts;

use App\Classes\BittrexKoinexArbitrageUtilities\ArbitrageOverviewUtilities;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use App\Classes\BittrexAPIs;
use Illuminate\Support\Facades\View;
use App\Models\BittrexKoinexArbitrageSettingsModel;
use App\Models\KoinexTickerDataVolumeModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

class ArbitrageOverviewController extends Controller
{
    public function index()
    {
        $arbitrageOverviewUtilities = new ArbitrageOverviewUtilities();
        $result = $arbitrageOverviewUtilities->findOpportunities();
//        return $result;
        return View::make('bittrex/bittrexkoinexarbitrage/arbitrage/overview/index', compact('result'));
    }

    public function viewJsonData()
    {
        $arbitrageOverviewUtilities = new ArbitrageOverviewUtilities();
        $result = $arbitrageOverviewUtilities->findOpportunities();
        return $result;
    }


    public function buyOnBittrex($marketName, $rate, $quantity)
    {

        $bittrexOrderResponseIndicator = true;
        $arbitrageOverviewUtilities = new ArbitrageOverviewUtilities();
        $result = $arbitrageOverviewUtilities->findOpportunities();
        $result['bittrexOrderResponseIndicator'] = $bittrexOrderResponseIndicator;
        return View::make('bittrex/bittrexkoinexarbitrage/arbitrage/overview/index', compact('result'));

        $bittrexOrderResponseIndicator = false;

        $settings = BittrexKoinexArbitrageSettingsModel::first();
        $apiKey = $settings->apiKey;
        $secretKey = $settings->secretKey;

        $bittrexAPIs = new BittrexAPIs($apiKey, $secretKey);

        try {

            $params = array(
                'market' => $marketName,
                'quantity' => $quantity,
                'rate' => $rate
            );
            $response = $bittrexAPIs->callAndGetFullResponse('market/buylimit', $params, true);

            Log::info(get_class($this) . '->' . __FUNCTION__ . ' buyOnBittrex details ' . serialize($response));

            if ($response->success == true) {
                $bittrexOrderResponseIndicator = true;
            }

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' buyOnBittrex exception: ' . $exception);
        }

        $arbitrageOverviewUtilities = new ArbitrageOverviewUtilities();
        $result = $arbitrageOverviewUtilities->findOpportunities();
        $result['bittrexOrderResponseIndicator'] = $bittrexOrderResponseIndicator;
        return View::make('bittrex/bittrexkoinexarbitrage/arbitrage/overview/index', compact('result'));

    }


    public function sellOnBittrex($marketName, $rate, $quantity)
    {
        $bittrexOrderResponseIndicator = true;
        $arbitrageOverviewUtilities = new ArbitrageOverviewUtilities();
        $result = $arbitrageOverviewUtilities->findOpportunities();
        $result['bittrexOrderResponseIndicator'] = $bittrexOrderResponseIndicator;
        return View::make('bittrex/bittrexkoinexarbitrage/arbitrage/overview/index', compact('result'));

        $bittrexOrderResponseIndicator = false;

        $settings = BittrexKoinexArbitrageSettingsModel::first();
        $apiKey = $settings->apiKey;
        $secretKey = $settings->secretKey;

        $bittrexAPIs = new BittrexAPIs($apiKey, $secretKey);

        try {

            $params = array(
                'market' => $marketName,
                'quantity' => $quantity,
                'rate' => $rate
            );
            $response = $bittrexAPIs->callAndGetFullResponse('market/selllimit', $params, true);

            Log::info(get_class($this) . '->' . __FUNCTION__ . ' buyOnBittrex details ' . serialize($response));

            if ($response->success == true) {
                $bittrexOrderResponseIndicator = true;
            }

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' buyOnBittrex exception: ' . $exception);
        }

        $arbitrageOverviewUtilities = new ArbitrageOverviewUtilities();
        $result = $arbitrageOverviewUtilities->findOpportunities();
        $result['bittrexOrderResponseIndicator'] = $bittrexOrderResponseIndicator;
        return View::make('bittrex/bittrexkoinexarbitrage/arbitrage/overview/index', compact('result'));
    }

    public function fakeKoinexMarketQuantities()
    {

        $currencyName = 'BTC';
        $koinexTickerDataFromDB = KoinexTickerDataVolumeModel::where('currencyName', '=', $currencyName)->first();
        $koinexTickerDataFromDB->buyVolume = 1;
        $koinexTickerDataFromDB->sellVolume = 1;
        $koinexTickerDataFromDB->save();

        $currencyName = 'BCC';
        $koinexTickerDataFromDB = KoinexTickerDataVolumeModel::where('currencyName', '=', $currencyName)->first();
        $koinexTickerDataFromDB->buyVolume = 1;
        $koinexTickerDataFromDB->sellVolume = 1;
        $koinexTickerDataFromDB->save();

        $currencyName = 'ETH';
        $koinexTickerDataFromDB = KoinexTickerDataVolumeModel::where('currencyName', '=', $currencyName)->first();
        $koinexTickerDataFromDB->buyVolume = 1;
        $koinexTickerDataFromDB->sellVolume = 1;
        $koinexTickerDataFromDB->save();

        $currencyName = 'LTC';
        $koinexTickerDataFromDB = KoinexTickerDataVolumeModel::where('currencyName', '=', $currencyName)->first();
        $koinexTickerDataFromDB->buyVolume = 10;
        $koinexTickerDataFromDB->sellVolume = 10;
        $koinexTickerDataFromDB->save();

        $currencyName = 'XRP';
        $koinexTickerDataFromDB = KoinexTickerDataVolumeModel::where('currencyName', '=', $currencyName)->first();
        $koinexTickerDataFromDB->buyVolume = 1000;
        $koinexTickerDataFromDB->sellVolume = 1000;
        $koinexTickerDataFromDB->save();

        return redirect('/bittrex/koinex/arbitrage/overview');
    }

    public function koinexOrderBook()
    {
        return View::make('bittrex/bittrexkoinexarbitrage/koinexorderbook/index');
    }

    public function bccBuyVolumeOrderBook()
    {

        $currencyName = 'BCC';

        $rules = array(
            'koinexBCCBuyVolume' => ['required', 'regex:/[0-9]+(,[0-9]+)*/'],
            'koinexBCCBuyPrice' => ['required', 'regex:/[0-9]+(,[0-9]+)*/'],
        );


        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook')
                ->withInput();
        } else {

            $koinexBCCBuyVolume = Input::get('koinexBCCBuyVolume');
            $koinexBCCBuyPrice = Input::get('koinexBCCBuyPrice');

            if($koinexBCCBuyVolume==$koinexBCCBuyPrice || $koinexBCCBuyVolume>$koinexBCCBuyPrice ){
                return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook');
            }

            $koinexTickerDataFromDB = KoinexTickerDataVolumeModel::where('currencyName', '=', $currencyName)->first();
            $koinexTickerDataFromDB->buyVolume = $koinexBCCBuyVolume;
            $koinexTickerDataFromDB->save();

            $koinexBCCBuyPrice = str_replace(",", "", $koinexBCCBuyPrice);
            $koinexTickerDataFromDB = KoinexTickerDataModel::where('currencyName', '=', $currencyName)->first();

            if( $koinexBCCBuyPrice >= $koinexTickerDataFromDB->minPrice24Hours && $koinexBCCBuyPrice <= $koinexTickerDataFromDB->maxPrice24Hours){
                $koinexTickerDataFromDB->bid = $koinexBCCBuyPrice;
                $koinexTickerDataFromDB->save();
            }

            return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook');
        }

    }

    public function ethBuyVolumeOrderBook()
    {

        $currencyName = 'ETH';

        $rules = array(
            'koinexETHBuyVolume' => ['required', 'regex:/[0-9]+(,[0-9]+)*/'],
            'koinexETHBuyPrice' => ['required', 'regex:/[0-9]+(,[0-9]+)*/'],
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook')
                ->withInput();
        } else {

            $koinexETHBuyVolume = Input::get('koinexETHBuyVolume');
            $koinexETHBuyPrice = Input::get('koinexETHBuyPrice');

            if($koinexETHBuyVolume==$koinexETHBuyPrice || $koinexETHBuyVolume>$koinexETHBuyPrice ){
                return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook');
            }

            $koinexTickerDataFromDB = KoinexTickerDataVolumeModel::where('currencyName', '=', $currencyName)->first();
            $koinexTickerDataFromDB->buyVolume = $koinexETHBuyVolume;
            $koinexTickerDataFromDB->save();

            $koinexETHBuyPrice = str_replace(",", "", $koinexETHBuyPrice);
            $koinexTickerDataFromDB = KoinexTickerDataModel::where('currencyName', '=', $currencyName)->first();

            if( $koinexETHBuyPrice >= $koinexTickerDataFromDB->minPrice24Hours && $koinexETHBuyPrice <= $koinexTickerDataFromDB->maxPrice24Hours){
                $koinexTickerDataFromDB->bid = $koinexETHBuyPrice;
                $koinexTickerDataFromDB->save();
            }

            return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook');
        }

    }

    public function ltcBuyVolumeOrderBook()
    {

        $currencyName = 'LTC';

        $rules = array(
            'koinexLTCBuyVolume' => ['required', 'regex:/[0-9]+(,[0-9]+)*/'],
            'koinexLTCBuyPrice' => ['required', 'regex:/[0-9]+(,[0-9]+)*/'],
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook')
                ->withInput();
        } else {

            $koinexLTCBuyVolume = Input::get('koinexLTCBuyVolume');
            $koinexLTCBuyPrice = Input::get('koinexLTCBuyPrice');

            if($koinexLTCBuyVolume==$koinexLTCBuyPrice || $koinexLTCBuyVolume>$koinexLTCBuyPrice ){
                return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook');
            }

            $koinexTickerDataFromDB = KoinexTickerDataVolumeModel::where('currencyName', '=', $currencyName)->first();
            $koinexTickerDataFromDB->buyVolume = $koinexLTCBuyVolume;
            $koinexTickerDataFromDB->save();

            $koinexLTCBuyPrice = str_replace(",", "", $koinexLTCBuyPrice);
            $koinexTickerDataFromDB = KoinexTickerDataModel::where('currencyName', '=', $currencyName)->first();

            if( $koinexLTCBuyPrice >= $koinexTickerDataFromDB->minPrice24Hours && $koinexLTCBuyPrice <= $koinexTickerDataFromDB->maxPrice24Hours){
                $koinexTickerDataFromDB->bid = $koinexLTCBuyPrice;
                $koinexTickerDataFromDB->save();
            }

            return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook');
        }

    }

    public function xrpBuyVolumeOrderBook()
    {

        $currencyName = 'XRP';

        $rules = array(
            'koinexXRPBuyVolume' => ['required', 'regex:/[0-9]+(,[0-9]+)*/'],
            'koinexXRPBuyPrice' => ['required', 'regex:/[0-9]+(,[0-9]+)*/'],
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook')
                ->withInput();
        } else {

            $koinexXRPBuyVolume = Input::get('koinexXRPBuyVolume');
            $koinexXRPBuyPrice = Input::get('koinexXRPBuyPrice');

            if($koinexXRPBuyVolume==$koinexXRPBuyPrice || $koinexXRPBuyVolume>$koinexXRPBuyPrice ){
                return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook');
            }

            $koinexTickerDataFromDB = KoinexTickerDataVolumeModel::where('currencyName', '=', $currencyName)->first();
            $koinexTickerDataFromDB->buyVolume = $koinexXRPBuyVolume;
            $koinexTickerDataFromDB->save();

            $koinexXRPBuyPrice = str_replace(",", "", $koinexXRPBuyPrice);
            $koinexTickerDataFromDB = KoinexTickerDataModel::where('currencyName', '=', $currencyName)->first();

            if( $koinexXRPBuyPrice >= $koinexTickerDataFromDB->minPrice24Hours && $koinexXRPBuyPrice <= $koinexTickerDataFromDB->maxPrice24Hours){
                $koinexTickerDataFromDB->bid = $koinexXRPBuyPrice;
                $koinexTickerDataFromDB->save();
            }

            return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook');
        }

    }


    public function btcBuyVolumeOrderBook()
    {

        $currencyName = 'BTC';

        $rules = array(
            'koinexBTCBuyVolume' => ['required', 'regex:/[0-9]+(,[0-9]+)*/'],
            'koinexBTCBuyPrice' => ['required', 'regex:/[0-9]+(,[0-9]+)*/'],
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook')
                ->withInput();
        } else {

            $koinexBTCBuyVolume = Input::get('koinexBTCBuyVolume');
            $koinexBTCBuyPrice = Input::get('koinexBTCBuyPrice');

            if($koinexBTCBuyVolume==$koinexBTCBuyPrice || $koinexBTCBuyVolume>$koinexBTCBuyPrice){
                return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook');
            }

            $koinexTickerDataFromDB = KoinexTickerDataVolumeModel::where('currencyName', '=', $currencyName)->first();
            $koinexTickerDataFromDB->buyVolume = $koinexBTCBuyVolume;
            $koinexTickerDataFromDB->save();

            $koinexBTCBuyPrice = str_replace(",", "", $koinexBTCBuyPrice);
            $koinexTickerDataFromDB = KoinexTickerDataModel::where('currencyName', '=', $currencyName)->first();

            if( $koinexBTCBuyPrice >= $koinexTickerDataFromDB->minPrice24Hours && $koinexBTCBuyPrice <= $koinexTickerDataFromDB->maxPrice24Hours){
                $koinexTickerDataFromDB->bid = $koinexBTCBuyPrice;
                $koinexTickerDataFromDB->save();
            }

            return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook');
        }

    }


    public function bccSellVolumeOrderBook()
    {

        $currencyName = 'BCC';

        $rules = array(
            'koinexBCCSellVolume' => ['required', 'regex:/[0-9]+(,[0-9]+)*/'],
            'koinexBCCSellPrice' => ['required', 'regex:/[0-9]+(,[0-9]+)*/'],

        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook')
                ->withInput();
        } else {

            $koinexBCCSellVolume = Input::get('koinexBCCSellVolume');
            $koinexBCCSellPrice = Input::get('koinexBCCSellPrice');

            if($koinexBCCSellVolume==$koinexBCCSellPrice || $koinexBCCSellVolume>$koinexBCCSellPrice){
                return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook');
            }

            $koinexTickerDataFromDB = KoinexTickerDataVolumeModel::where('currencyName', '=', $currencyName)->first();

            $koinexTickerDataFromDB->sellVolume = $koinexBCCSellVolume;
            $koinexTickerDataFromDB->save();

            $koinexBCCSellPrice = str_replace(",", "", $koinexBCCSellPrice);
            $koinexTickerDataFromDB = KoinexTickerDataModel::where('currencyName', '=', $currencyName)->first();

            if( $koinexBCCSellPrice >= $koinexTickerDataFromDB->minPrice24Hours && $koinexBCCSellPrice <= $koinexTickerDataFromDB->maxPrice24Hours){
                $koinexTickerDataFromDB->ask = $koinexBCCSellPrice;
                $koinexTickerDataFromDB->save();
            }

            return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook');
        }

    }

    public function ethSellVolumeOrderBook()
    {

        $currencyName = 'ETH';

        $rules = array(
            'koinexETHSellVolume' => ['required', 'regex:/[0-9]+(,[0-9]+)*/'],
            'koinexETHSellPrice' => ['required', 'regex:/[0-9]+(,[0-9]+)*/'],
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook')
                ->withInput();
        } else {

            $koinexETHSellVolume = Input::get('koinexETHSellVolume');
            $koinexETHSellPrice = Input::get('koinexETHSellPrice');

            if($koinexETHSellVolume==$koinexETHSellPrice || $koinexETHSellVolume>$koinexETHSellPrice){
                return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook');
            }

            $koinexTickerDataFromDB = KoinexTickerDataVolumeModel::where('currencyName', '=', $currencyName)->first();

            $koinexTickerDataFromDB->sellVolume = $koinexETHSellVolume;
            $koinexTickerDataFromDB->save();

            $koinexETHSellPrice = str_replace(",", "", $koinexETHSellPrice);
            $koinexTickerDataFromDB = KoinexTickerDataModel::where('currencyName', '=', $currencyName)->first();

            if( $koinexETHSellPrice >= $koinexTickerDataFromDB->minPrice24Hours && $koinexETHSellPrice <= $koinexTickerDataFromDB->maxPrice24Hours){
                $koinexTickerDataFromDB->ask = $koinexETHSellPrice;
                $koinexTickerDataFromDB->save();
            }

            return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook');
        }

    }


    public function ltcSellVolumeOrderBook()
    {

        $currencyName = 'LTC';

        $rules = array(
            'koinexLTCSellVolume' => ['required', 'regex:/[0-9]+(,[0-9]+)*/'],
            'koinexLTCSellPrice' => ['required', 'regex:/[0-9]+(,[0-9]+)*/'],
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook')
                ->withInput();
        } else {

            $koinexLTCSellVolume = Input::get('koinexLTCSellVolume');
            $koinexLTCSellPrice = Input::get('koinexLTCSellPrice');

            if($koinexLTCSellVolume==$koinexLTCSellPrice || $koinexLTCSellVolume>$koinexLTCSellPrice ){
                return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook');
            }

            $koinexTickerDataFromDB = KoinexTickerDataVolumeModel::where('currencyName', '=', $currencyName)->first();

            $koinexTickerDataFromDB->sellVolume = $koinexLTCSellVolume;
            $koinexTickerDataFromDB->save();

            $koinexLTCSellPrice = str_replace(",", "", $koinexLTCSellPrice);
            $koinexTickerDataFromDB = KoinexTickerDataModel::where('currencyName', '=', $currencyName)->first();

            if( $koinexLTCSellPrice >= $koinexTickerDataFromDB->minPrice24Hours && $koinexLTCSellPrice <= $koinexTickerDataFromDB->maxPrice24Hours){
                $koinexTickerDataFromDB->ask = $koinexLTCSellPrice;
                $koinexTickerDataFromDB->save();
            }

            return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook');
        }

    }

    public function xrpSellVolumeOrderBook()
    {

        $currencyName = 'XRP';

        $rules = array(
            'koinexXRPSellVolume' => ['required', 'regex:/[0-9]+(,[0-9]+)*/'],
            'koinexXRPSellPrice' => ['required', 'regex:/[0-9]+(,[0-9]+)*/'],
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook')
                ->withInput();
        } else {

            $koinexXRPSellVolume = Input::get('koinexXRPSellVolume');
            $koinexXRPSellPrice = Input::get('koinexXRPSellPrice');

            if($koinexXRPSellVolume==$koinexXRPSellPrice || $koinexXRPSellVolume>$koinexXRPSellPrice ){
                return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook');
            }

            $koinexTickerDataFromDB = KoinexTickerDataVolumeModel::where('currencyName', '=', $currencyName)->first();

            $koinexTickerDataFromDB->sellVolume = $koinexXRPSellVolume;
            $koinexTickerDataFromDB->save();

            $koinexXRPSellPrice = str_replace(",", "", $koinexXRPSellPrice);
            $koinexTickerDataFromDB = KoinexTickerDataModel::where('currencyName', '=', $currencyName)->first();

            if( $koinexXRPSellPrice >= $koinexTickerDataFromDB->minPrice24Hours && $koinexXRPSellPrice <= $koinexTickerDataFromDB->maxPrice24Hours){
                $koinexTickerDataFromDB->ask = $koinexXRPSellPrice;
                $koinexTickerDataFromDB->save();
            }

            return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook');
        }

    }

    public function btcSellVolumeOrderBook()
    {

        $currencyName = 'BTC';

        $rules = array(
            'koinexBTCSellVolume' => ['required', 'regex:/[0-9]+(,[0-9]+)*/'],
            'koinexBTCSellPrice' => ['required', 'regex:/[0-9]+(,[0-9]+)*/'],
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook')
                ->withInput();
        } else {

            $koinexBTCSellVolume = Input::get('koinexBTCSellVolume');
            $koinexBTCSellPrice = Input::get('koinexBTCSellPrice');

            if($koinexBTCSellVolume==$koinexBTCSellPrice || $koinexBTCSellVolume>$koinexBTCSellPrice){
                return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook');
            }

            $koinexTickerDataFromDB = KoinexTickerDataVolumeModel::where('currencyName', '=', $currencyName)->first();

            $koinexTickerDataFromDB->sellVolume = $koinexBTCSellVolume;
            $koinexTickerDataFromDB->save();

            $koinexBTCSellPrice = str_replace(",", "", $koinexBTCSellPrice);
            $koinexTickerDataFromDB = KoinexTickerDataModel::where('currencyName', '=', $currencyName)->first();

            if( $koinexBTCSellPrice >= $koinexTickerDataFromDB->minPrice24Hours && $koinexBTCSellPrice <= $koinexTickerDataFromDB->maxPrice24Hours){
                $koinexTickerDataFromDB->ask = $koinexBTCSellPrice;
                $koinexTickerDataFromDB->save();
            }

            return Redirect::to('bittrex/koinex/arbitrage/koinex/orderbook');
        }

    }

}
