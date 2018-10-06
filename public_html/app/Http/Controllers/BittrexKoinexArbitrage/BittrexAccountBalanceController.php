<?php

namespace App\Http\Controllers\BittrexKoinexArbitrage;

use App\Classes\BittrexAccountUtilities;
use App\Models\BittrexKoinexArbitrageSettingsModel;
use Log;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;

class BittrexAccountBalanceController extends Controller
{

    public function index()
    {
        try {
            $clientData = BittrexKoinexArbitrageSettingsModel::first();
            $apiKey = $clientData->apiKey;
            $secretKey = $clientData->secretKey;

            $bittrexAccountUtilities = new BittrexAccountUtilities($apiKey,$secretKey);
            $altcoinsBalanceData = $bittrexAccountUtilities->getCryptoCurrenciesBalance();

            $altcoinsBalance =  $altcoinsBalanceData['altcoinsBalance'];
            $totalBalanceIn_BTC = $altcoinsBalanceData['totalBalanceIn_BTC'];
            $totalBalanceIn_USDT = $altcoinsBalanceData['totalBalanceIn_USDT'];
            $accountBalance = $altcoinsBalanceData['accountBalance'];
            $accountError = $altcoinsBalanceData['accountError'];

        } catch (Exception $exception) {
            Log::info(get_class($this).'->'.__FUNCTION__ .' exception: ' . $exception);
        }
        return View::make('bittrex/bittrexkoinexarbitrage/bittrexaccountbalance/index', compact('altcoinsBalance', 'totalBalanceIn_BTC', 'totalBalanceIn_USDT', 'accountError', 'accountBalance','clientData'));

    }


}
