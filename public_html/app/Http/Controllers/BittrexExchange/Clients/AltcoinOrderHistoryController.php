<?php

namespace App\Http\Controllers\BittrexExchange\Clients;

use App\Models\ClientsListModel;
use App\Classes\BittrexGeneralUtilities;
use Log;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;

class AltcoinOrderHistoryController extends Controller
{

    public function index($id, $marketName)
    {
        $clientData = ClientsListModel::find($id);

        try {

            $apiKey = $clientData->apiKey;
            $secretKey = $clientData->secretKey;
            $bittrexGeneralUtilities = new BittrexGeneralUtilities($apiKey, $secretKey);
            $data = $bittrexGeneralUtilities->getAltcoinOrderHistory($marketName);

            $orderHistory = $data['orderHistory'];
            $openOrders = $data['openOrders'];
            $ticker = $data['ticker'];
            $percentChange = $data['percentChange'];
            $last24HoursPercentChange = $data['last24HoursPercentChange'];
            $accountError = null;


        } catch (Exception $exception) {
            Log::info(get_class($this).'->'.__FUNCTION__ . ' altcoinOrderHistory exception: ' . 'inputs' . serialize(Input::All()) . '   exception ' . $exception);
            $accountError = $exception->getMessage();
        }
        return View::make('bittrex/clients/exchange/altcoinorderhistory/index', compact('orderHistory', 'openOrders', 'ticker', 'marketName', 'clientData', 'percentChange', 'last24HoursPercentChange', 'accountError'));

    }

}
