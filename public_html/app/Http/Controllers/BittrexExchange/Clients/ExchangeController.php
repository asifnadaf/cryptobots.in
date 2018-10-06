<?php

namespace App\Http\Controllers\BittrexExchange\Clients;

use App\Models\ClientsListModel;
use App\Classes\BittrexGeneralUtilities;
use App\Classes\BittrexAccountUtilities;
use App\Classes\BittrexAPIs;
use Log;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;


class ExchangeController extends Controller
{
    public function index($clientId)
    {
        try {

            $clientData = ClientsListModel::find($clientId);
            $apiKey = $clientData->apiKey;
            $secretKey = $clientData->secretKey;

            $bittrexAccountUtilities = new BittrexAccountUtilities($apiKey, $secretKey);
            $accountBalance = $bittrexAccountUtilities->getUSDTAndBTCBalance();

            $bittrexGeneralUtilities = new BittrexGeneralUtilities($apiKey, $secretKey);
            $openOrders = $bittrexGeneralUtilities->getExchangeOpenOrders();

            $accountError = null;
        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . 'exception: ' . 'inputs' . serialize(Input::All()) . '   exception' . $exception);
            $openOrders = [];
            $accountError = 'An exception has occurred, please contact support team for help: ' . $exception->getMessage();
        }
        return View::make('bittrex/clients/exchange/index', compact('openOrders', 'accountBalance', 'clientData', 'accountError'));
    }

    public function buyLimit()
    {

        $id = Input::get('id');
        $marketName = Input::get('marketName');
        $quantity = Input::get('quantity');
        $rate = Input::get('rate');

        $bittrexMinimumInvestmentSize = 0.00050000;             // Minimum investment amount restrictions from exchange.

        $clientslist = ClientsListModel::find($id);

        $apiKey = $clientslist->apiKey;
        $secretKey = $clientslist->secretKey;

        $pauseTrading = $clientslist->pauseTrading;

        $buyLimitResponse = null;
        if (strcasecmp($pauseTrading, 'Yes') == 0) {
            $buyLimitResponse['status'] = "buyLimit: Account trading is paused. Edit clients details to enable it";
            Log::info(get_class($this) . '->' . __FUNCTION__ . $buyLimitResponse['status']);
            return $buyLimitResponse;
        }

        $bittrexAPIs = new BittrexAPIs($apiKey, $secretKey);

        try {
            if ($quantity * $rate >= $bittrexMinimumInvestmentSize) {
                Log::info(get_class($this) . '->' . __FUNCTION__ . 'About to buyLimit for client ' . serialize($clientslist));
                $buyLimitResponse['status'] = $bittrexAPIs->buyLimit($marketName, $quantity, $rate);
                Log::info(get_class($this) . '->' . __FUNCTION__ . ' Purchase details ' . serialize($buyLimitResponse['status']));
            }
            {
                $buyLimitResponse['status'] = 'Buy limit order is not placed because it does not meet minimum investments requirement of exchange $marketName ' . $marketName . ' $quantity ' . $quantity . ' rate ' . $rate;
                Log::info(get_class($this) . '->' . __FUNCTION__ . $buyLimitResponse['status']);
            }

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'inputs' . serialize(Input::All()) . '   exception ' . $exception);
            $buyLimitResponse['status'] = $exception->getMessage();
        }
        return $buyLimitResponse;
    }

    public function sellLimit()
    {

        $id = Input::get('id');
        $marketName = Input::get('marketName');
        $quantity = Input::get('quantity');
        $rate = Input::get('rate');

        $bittrexMinimumInvestmentSize = 0.00050000;             // Minimum investment amount restrictions from exchange.

        $clientslist = ClientsListModel::find($id);

        $apiKey = $clientslist->apiKey;
        $secretKey = $clientslist->secretKey;

        $pauseTrading = $clientslist->pauseTrading;

        $sellLimitResponse = null;
        if (strcasecmp($pauseTrading, 'Yes') == 0) {
            $sellLimitResponse['status'] = "sellLimit: Account trading is paused. Edit clients details to enable it";
            Log::info(get_class($this) . '->' . __FUNCTION__ . $sellLimitResponse['status']);
            return $sellLimitResponse;
        }

        $bittrexAPIs = new BittrexAPIs($apiKey, $secretKey);

        try {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' client ' . serialize($clientslist));
            if ($quantity * $rate >= $bittrexMinimumInvestmentSize) {
                $sellLimitResponse['status'] = $bittrexAPIs->sellLimit($marketName, $quantity, $rate);
                Log::info(get_class($this) . '->' . __FUNCTION__ . ' sellLimit details ' . serialize($sellLimitResponse['status']));
            } else {
                $sellLimitResponse['status'] = 'Sell limit order is not placed because it does not meet minimum investments requirement of exchange $marketName ' . $marketName . ' $quantity ' . $quantity . ' rate ' . $rate;
                Log::info(get_class($this) . '->' . __FUNCTION__ . $sellLimitResponse['status']);
            }

        } catch (Exception $exception) {
            $sellLimitResponse['status'] = $exception->getMessage();
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'inputs' . serialize(Input::All()) . '   exception ' . $exception);
        }
        return $sellLimitResponse;
    }


}
