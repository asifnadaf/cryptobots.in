<?php

namespace App\Http\Controllers\BittrexExchange;

use App\Models\ClientsListModel;
use App\Classes\BittrexAccountUtilities;
use Log;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;

class OrderHistoryController extends Controller
{

    public function index($clientId)
    {
        try {
            $clientData = ClientsListModel::find($clientId);
            $apiKey = $clientData->apiKey;
            $secretKey = $clientData->secretKey;

            $bittrexAccountUtilities = new BittrexAccountUtilities($apiKey,$secretKey);
            $orderHistory = $bittrexAccountUtilities->getOrderHistory();
            $accountBalance = $bittrexAccountUtilities->getUSDTAndBTCBalance();
            $accountError = null;;

        } catch (Exception $exception) {
            Log::info(get_class($this).'->'.__FUNCTION__ . ' BalanceHistoryController index exception: ' . $exception);
        }
        return view::make('bittrex/clients/orderhistory/index', compact('orderHistory', 'clientData','accountBalance','accountError'));

    }


}
