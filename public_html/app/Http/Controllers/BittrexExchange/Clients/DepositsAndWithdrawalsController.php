<?php

namespace App\Http\Controllers\BittrexExchange\Clients;

use App\Models\ClientsListModel;
use App\Classes\BittrexAPIs;
use App\Classes\BittrexAccountUtilities;
use Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class DepositsAndWithdrawalsController extends Controller
{

    public function index($clientId)
    {
        try {
            $clientData = ClientsListModel::find($clientId);
            $apiKey = $clientData->apiKey;
            $secretKey = $clientData->secretKey;

            $bittrexAPIs = new BittrexAPIs($apiKey, $secretKey);
            $withdrawalHistory = $bittrexAPIs->getWithdrawalHistory();
            $depositHistory = $bittrexAPIs->getDepositHistory();
            $bittrexAccountUtilities = new BittrexAccountUtilities($apiKey,$secretKey);
            $accountBalance = $bittrexAccountUtilities->getUSDTAndBTCBalance();

            $accountError = null;

        } catch (Exception $exception) {
            $accountError = $exception;
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' BalanceHistoryController index exception: ' . $exception);
        }
        return view::make('bittrex/clients/depositsandwithdrawals/index', compact('clientData', 'withdrawalHistory', 'depositHistory', 'accountBalance','accountError'));

    }

}
