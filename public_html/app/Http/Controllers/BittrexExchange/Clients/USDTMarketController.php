<?php

namespace App\Http\Controllers\BittrexExchange\Clients;

use App\Models\ClientsListModel;
use Log;
use App\Classes\BittrexGeneralUtilities;
use App\Classes\BittrexBuyUtilities;
use App\Classes\BittrexAPIs;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Psy\Test\Exception\RuntimeExceptionTest;
use LaravelAcl\Authentication\Classes\SentryAuthenticator;
use LaravelAcl\Authentication\Helpers\SentryAuthenticationHelper;


class USDTMarketController extends Controller
{
    var $bittrexMinimumInvestmentLimits = 0.00050000;

    public function buyTetherEdit()
    {

        $sentryAuthenticationHelper = new SentryAuthenticationHelper();
        $isSuperAdminPermission = $sentryAuthenticationHelper->hasPermission(array('_superadmin'));
        $isAgentPermission = $sentryAuthenticationHelper->hasPermission(array('_agent'));

        if($isSuperAdminPermission){
            $clientsList = ClientsListModel::all();
        }elseif($isAgentPermission){
            $sentryAuthenticator = new SentryAuthenticator();
            $loggedAgentId = $sentryAuthenticator->getLoggedUser()->id;
            $clientsList = ClientsListModel::where('agentId', '=', $loggedAgentId)->get();
        }

        $countClients = count($clientsList);
        $allClientsIds = '';
        $i = 0;
        foreach ($clientsList as $client) {
            $i++;
            if ($i < $countClients) {
                $allClientsIds = $allClientsIds . $client->id . ',';
            } else {
                $allClientsIds = $allClientsIds . $client->id;
            }
        }
        return View::make('bittrex/clients/usdtmarket/buytether', compact('allClientsIds'));
    }

    public function buyTether()
    {

        $rules = array(
            'clientsIdList' => 'required',
            'marketName' => 'required',
            'rate' => 'required'
        );

        $validationErrorMessages = array(
            'clientsIdList.required' => "Clients ids cannot be empty",
            'marketName.required' => "Market name cannot be empty",
            'rate.required' => "Sell price cannot be empty",
        );

        $validator = Validator::make(Input::all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            return Redirect::to('clients/settings/tether/for/all/clients')
                ->withInput()->withErrors($validator);
        } else {

            $marketName = Input::get('marketName');
            $rate = Input::get('rate');

            $multipleClientsId = Input::get('clientsIdList');
            $multipleClientsId = explode(',', $multipleClientsId);

            foreach ($multipleClientsId as $clientId) {

                $clientData = ClientsListModel::find($clientId);
                $apiKey = $clientData->apiKey;
                $secretKey = $clientData->secretKey;

                $pauseTrading = $clientData->pauseTrading;
                if (strcasecmp($pauseTrading, 'Yes') == 0) {
                    continue;
                }

                $bittrexAPIs = new BittrexAPIs($apiKey, $secretKey);

                $altcoinsName = str_replace("USDT-", "", $marketName);
                $alcoinBalance = $bittrexAPIs->getBalance($altcoinsName);
                $quantity = $alcoinBalance->Available;
                if ($quantity >= $this->bittrexMinimumInvestmentLimits) {
//                    Log::info(get_class($this) . '->' . __FUNCTION__ . ' Market Name ' . $marketName . ' $quantity ' . $quantity . ' $rate ' . $rate);
                    $openOrders = $bittrexAPIs->getOpenOrders($marketName);
                    if (count($openOrders)) {
                        foreach ($openOrders as $openOrder)
                            $orderUuid = $openOrder->OrderUuid;
                        if ($orderUuid != null) {
                            $bittrexAPIs->cancel($orderUuid);
                            sleep(1);
                        }
                    }
                    $bittrexAPIs->sellLimit($marketName, $quantity, $rate);
                    $this->createLogRecordsOfBuying($clientData, $marketName, $quantity, $rate);

                }
//                else {
//                    Log::info(get_class($this) . '->' . __FUNCTION__ . ' Sell limit order is not placed because it does not meet minimum investments requirement of exchange $marketName ' . $marketName . ' $quantity ' . $quantity . ' rate ' . $rate);
//                }
            }

            return Redirect::to('/clients/settings/tether/for/all/clients');
        }
    }



    public function cancelBuyTetherEdit()
    {

        $sentryAuthenticationHelper = new SentryAuthenticationHelper();
        $isSuperAdminPermission = $sentryAuthenticationHelper->hasPermission(array('_superadmin'));
        $isAgentPermission = $sentryAuthenticationHelper->hasPermission(array('_agent'));

        if($isSuperAdminPermission){
            $clientsList = ClientsListModel::all();
        }elseif($isAgentPermission){
            $sentryAuthenticator = new SentryAuthenticator();
            $loggedAgentId = $sentryAuthenticator->getLoggedUser()->id;
            $clientsList = ClientsListModel::where('agentId', '=', $loggedAgentId)->get();
        }

        $countClients = count($clientsList);
        $allClientsIds = '';
        $i = 0;
        foreach ($clientsList as $client) {
            $i++;
            if ($i < $countClients) {
                $allClientsIds = $allClientsIds . $client->id . ',';
            } else {
                $allClientsIds = $allClientsIds . $client->id;
            }
        }
        return View::make('bittrex/clients/usdtmarket/cancelbuytether', compact('allClientsIds'));
    }

    public function cancelBuyTether()
    {

        $rules = array(
            'clientsIdList' => 'required',
            'marketName' => 'required',
        );

        $validationErrorMessages = array(
            'clientsIdList.required' => "Clients ids cannot be empty",
            'marketName.required' => "Market name cannot be empty",
        );

        $validator = Validator::make(Input::all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            return Redirect::to('clients/cancel/settings/tether/for/all/clients')
                ->withInput()->withErrors($validator);
        } else {

            $marketName = Input::get('marketName');

            $multipleClientsId = Input::get('clientsIdList');
            $multipleClientsId = explode(',', $multipleClientsId);

            foreach ($multipleClientsId as $clientId) {

                $clientData = ClientsListModel::find($clientId);
                $apiKey = $clientData->apiKey;
                $secretKey = $clientData->secretKey;

                $pauseTrading = $clientData->pauseTrading;
                if (strcasecmp($pauseTrading, 'Yes') == 0) {
                    continue;
                }

                $bittrexAPIs = new BittrexAPIs($apiKey, $secretKey);
                $openOrders = $bittrexAPIs->getOpenOrders($marketName);
                if (count($openOrders)) {
                    foreach ($openOrders as $openOrder){
                        $orderUuid = $openOrder->OrderUuid;
                        if ($orderUuid != null) {
                            $bittrexAPIs->cancel($orderUuid);
                        }
                    }
                }
            }

            return Redirect::to('/clients');
        }
    }


    public function sellTetherEdit()
    {

        $sentryAuthenticationHelper = new SentryAuthenticationHelper();
        $isSuperAdminPermission = $sentryAuthenticationHelper->hasPermission(array('_superadmin'));
        $isAgentPermission = $sentryAuthenticationHelper->hasPermission(array('_agent'));

        if($isSuperAdminPermission){
            $clientsList = ClientsListModel::all();
        }elseif($isAgentPermission){
            $sentryAuthenticator = new SentryAuthenticator();
            $loggedAgentId = $sentryAuthenticator->getLoggedUser()->id;
            $clientsList = ClientsListModel::where('agentId', '=', $loggedAgentId)->get();
        }

        $countClients = count($clientsList);
        $allClientsIds = '';
        $i = 0;
        foreach ($clientsList as $client) {
            $i++;
            if ($i < $countClients) {
                $allClientsIds = $allClientsIds . $client->id . ',';
            } else {
                $allClientsIds = $allClientsIds . $client->id;
            }
        }
        return View::make('bittrex/clients/usdtmarket/selltether', compact('allClientsIds'));
    }


    public function sellTether()
    {
        $rules = array(
            'clientsIdList' => 'required',
            'marketName' => 'required',
            'rate' => 'required'
        );

        $validationErrorMessages = array(
            'clientsIdList.required' => "Clients ids cannot be empty",
            'marketName.required' => "Market name cannot be empty",
            'rate.required' => "Buy price cannot be empty",
        );

        $validator = Validator::make(Input::all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            return Redirect::to('clients/sell/tether/for/all/clients')
                ->withInput()->withErrors($validator);
        } else {

            $marketName = Input::get('marketName');
            $rate = Input::get('rate');

            $multipleClientsId = Input::get('clientsIdList');
            $multipleClientsId = explode(',', $multipleClientsId);

            foreach ($multipleClientsId as $clientId) {

                $clientData = ClientsListModel::find($clientId);
                $apiKey = $clientData->apiKey;
                $secretKey = $clientData->secretKey;

                $pauseTrading = $clientData->pauseTrading;
                if (strcasecmp($pauseTrading, 'Yes') == 0) {
                    continue;
                }

                $bittrexAPIs = new BittrexAPIs($apiKey, $secretKey);

                $tetherBalance = $bittrexAPIs->getBalance('USDT');

                $quantity = $tetherBalance->Available/$rate*0.99;

                if ($quantity >= $this->bittrexMinimumInvestmentLimits) {
//                    Log::info(get_class($this) . '->' . __FUNCTION__ . ' Market Name ' . $marketName . ' $quantity ' . $quantity . ' $rate ' . $rate);
                    $openOrders = $bittrexAPIs->getOpenOrders($marketName);
                    if (count($openOrders)) {
                        foreach ($openOrders as $openOrder)
                            $orderUuid = $openOrder->OrderUuid;
                        if ($orderUuid != null) {
                            $bittrexAPIs->cancel($orderUuid);
                            sleep(1);
                        }
                    }
                    $bittrexAPIs->buyLimit($marketName, $quantity, $rate);
                    $this->createLogRecordsOfSelling($clientData, $marketName, $quantity, $rate);

                }
//                else {
//                    Log::info(get_class($this) . '->' . __FUNCTION__ . ' Sell limit order is not placed because it does not meet minimum investments requirement of exchange $marketName ' . $marketName . ' $quantity ' . $quantity . ' rate ' . $rate);
//                }
            }

            return Redirect::to('/clients/sell/tether/for/all/clients');
        }
    }



    public function cancelSellTetherEdit()
    {

        $sentryAuthenticationHelper = new SentryAuthenticationHelper();
        $isSuperAdminPermission = $sentryAuthenticationHelper->hasPermission(array('_superadmin'));
        $isAgentPermission = $sentryAuthenticationHelper->hasPermission(array('_agent'));

        if($isSuperAdminPermission){
            $clientsList = ClientsListModel::all();
        }elseif($isAgentPermission){
            $sentryAuthenticator = new SentryAuthenticator();
            $loggedAgentId = $sentryAuthenticator->getLoggedUser()->id;
            $clientsList = ClientsListModel::where('agentId', '=', $loggedAgentId)->get();
        }

        $countClients = count($clientsList);
        $allClientsIds = '';
        $i = 0;
        foreach ($clientsList as $client) {
            $i++;
            if ($i < $countClients) {
                $allClientsIds = $allClientsIds . $client->id . ',';
            } else {
                $allClientsIds = $allClientsIds . $client->id;
            }
        }
        return View::make('bittrex/clients/usdtmarket/cancelselltether', compact('allClientsIds'));
    }


    public function cancelSellTether()
    {
        $rules = array(
            'clientsIdList' => 'required',
            'marketName' => 'required',
        );

        $validationErrorMessages = array(
            'clientsIdList.required' => "Clients ids cannot be empty",
            'marketName.required' => "Market name cannot be empty",
        );

        $validator = Validator::make(Input::all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            return Redirect::to('clients/cancel/sell/tether/for/all/clients')
                ->withInput()->withErrors($validator);
        } else {

            $marketName = Input::get('marketName');

            $multipleClientsId = Input::get('clientsIdList');
            $multipleClientsId = explode(',', $multipleClientsId);

            foreach ($multipleClientsId as $clientId) {

                $clientData = ClientsListModel::find($clientId);
                $apiKey = $clientData->apiKey;
                $secretKey = $clientData->secretKey;

                $pauseTrading = $clientData->pauseTrading;
                if (strcasecmp($pauseTrading, 'Yes') == 0) {
                    continue;
                }

                $bittrexAPIs = new BittrexAPIs($apiKey, $secretKey);

                $openOrders = $bittrexAPIs->getOpenOrders($marketName);
                if (count($openOrders)) {
                    foreach ($openOrders as $openOrder){
                        $orderUuid = $openOrder->OrderUuid;
                        if ($orderUuid != null) {
                            $bittrexAPIs->cancel($orderUuid);
                        }
                    }
                }
            }

            return Redirect::to('/clients');
        }
    }



    public function createLogRecordsOfBuying($clientData, $marketName, $quantity, $rate)
    {

        try {
//            Log::info('');
//            Log::info(get_class($this) . '->' . __FUNCTION__);
//            Log::info('USDT MARKET - Sell Altcoin / Buy USDT Tether');
//            Log::info(get_class($this) . '->' . __FUNCTION__ . 'Client name ' . $clientData->fullName);
//            Log::info(get_class($this) . '->' . __FUNCTION__ . 'MarketName ' . $marketName);
//            Log::info(get_class($this) . '->' . __FUNCTION__ . 'rate ' . $rate);
//            Log::info(get_class($this) . '->' . __FUNCTION__ . 'quantity ' . $quantity);
//            Log::info('');

        } catch (Exception $exception) {
//            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

    }


    public function createLogRecordsOfSelling($clientData, $marketName, $quantity, $rate)
    {

        try {
//            Log::info(get_class($this) . '->' . __FUNCTION__);
//            Log::info('USDT MARKET - Buy Altcoin / Sell USDT Tether');
//            Log::info(get_class($this) . '->' . __FUNCTION__ . 'Client name ' . $clientData->fullName);
//            Log::info(get_class($this) . '->' . __FUNCTION__ . 'MarketName ' . $marketName);
//            Log::info(get_class($this) . '->' . __FUNCTION__ . 'rate ' . $rate);
//            Log::info(get_class($this) . '->' . __FUNCTION__ . 'quantity ' . $quantity);
//            Log::info('');
        } catch (Exception $exception) {
//            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

    }


}
