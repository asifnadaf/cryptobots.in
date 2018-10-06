<?php

namespace App\Http\Controllers\BittrexExchange;

use App\Classes\BittrexGeneralUtilities;
use App\Models\ClientsListModel;
use Log;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;

class BalanceHistoryController extends Controller
{

    public function index($clientId)
    {
        try {
            $clientData = ClientsListModel::find($clientId);
            $apiKey = $clientData->apiKey;
            $secretKey = $clientData->secretKey;

            $bittrexGeneralUtilities = new BittrexGeneralUtilities($apiKey,$secretKey);
            $balanceHistoryData = $bittrexGeneralUtilities->getBalanceHistory($clientData->id);

            $balanceHistory =  $balanceHistoryData['balanceHistory'];
            $totalBalanceIn_BTC = $balanceHistoryData['totalBalanceIn_BTC'];
            $totalBalanceIn_USDT = $balanceHistoryData['totalBalanceIn_USDT'];
            $accountBalance = $balanceHistoryData['accountBalance'];
            $accountError = $balanceHistoryData['accountError'];

        } catch (Exception $exception) {
            Log::info(get_class($this).'->'.__FUNCTION__ .' exception: ' . $exception);
        }
        return View::make('bittrex/clients/balancehistory/index', compact('balanceHistory', 'totalBalanceIn_BTC', 'totalBalanceIn_USDT', 'accountError', 'accountBalance','clientData'));
    }


}
