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
use LaravelAcl\Authentication\Classes\SentryAuthenticator;
use LaravelAcl\Authentication\Helpers\SentryAuthenticationHelper;


class ClientsUtilitiesController extends Controller
{
    var $bittrexMinimumInvestmentLimits = 0.00050000;

    public function resetSellLimitOrdersByXFactorForAllClientsEdit()
    {
        return View::make('bittrex/clients/utilities/resetlimitsellorderbyxfactorsforallclients');
    }


    public function resetSellLimitOrdersByXFactorForAllClientsUpdate()
    {
        $rules = array(
            'multiplicationFactor' => 'required | numeric'
        );

        $validationErrorMessages = array(
            'multiplicationFactor.required' => "Multiplication factor cannot be empty",
            'multiplicationFactor.numeric' => "Multiplication factor must be numeric",
        );

        $validator = Validator::make(Input::all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            return Redirect::to('clients/reset/sell/limit/orders/all/clients')
                ->withInput()->withErrors($validator);
        } else {
            $multiplicationFactor = Input::get('multiplicationFactor');
            if ($multiplicationFactor <= 1) {
                return Redirect::to('clients/reset/sell/limit/orders/all/clients')
                    ->withInput()->withErrors($validator);
            }

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

            foreach ($clientsList as $row) {
                $apiKey = $row->apiKey;
                $secretKey = $row->secretKey;

                $pauseTrading = $row->pauseTrading;
                if (strcasecmp($pauseTrading, 'Yes') == 0) {
                    continue;
                }

                $bittrexGeneralUtilities = new BittrexGeneralUtilities($apiKey, $secretKey);
                $bittrexGeneralUtilities->cancelAllSellLimit();
                $bittrexGeneralUtilities->createAllSellLimit($multiplicationFactor);
            }

            return Redirect::to('/clients');
        }
    }

    public function resetSpecificSellLimitOrdersEdit()
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

        return View::make('bittrex/clients/utilities/resetspecificlimitsellorder', compact('allClientsIds'));
    }


    public function resetSpecificSellLimitOrdersUpdate()
    {
        $rules = array(
            'altcoinsName' => 'required',
            'clientsIdList' => 'required'
        );

        $validationErrorMessages = array(
            'altcoinsName.required' => "Altcoins name cannot be empty",
            'clientsIdList.required' => "Clients ids cannot be empty",
        );

        $validator = Validator::make(Input::all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            return Redirect::to('clients/reset/specific/sell/limit/orders')
                ->withInput()->withErrors($validator);
        } else {

            $multipleClientsId = Input::get('clientsIdList');
            $multipleClientsId = explode(',', $multipleClientsId);

            $altcoinsName = Input::get('altcoinsName');
            $altcoinsName = str_replace("BTC-", "", $altcoinsName);
            $altcoinsName = explode(',', $altcoinsName);

            foreach ($multipleClientsId as $clientId) {

                $clientData = ClientsListModel::find($clientId);
                $apiKey = $clientData->apiKey;
                $secretKey = $clientData->secretKey;

                $pauseTrading = $clientData->pauseTrading;
                if (strcasecmp($pauseTrading, 'Yes') == 0) {
                    continue;
                }

                $bittrexGeneralUtilities = new BittrexGeneralUtilities($apiKey, $secretKey);
                $bittrexGeneralUtilities->sellSpecificMarket($clientData, $altcoinsName);
            }

            return Redirect::to('/clients');

        }
    }

    public function sellAllAltcoinsOfSingleClientEdit()
    {
        return View::make('bittrex/clients/utilities/sellallaltcoinsofclient');
    }


    public function sellAllAltcoinsOfSingleClientUpdate()
    {
        $rules = array(
            'multiplicationFactor' => 'required | numeric',
            'clientId' => 'required'
        );

        $validationErrorMessages = array(
            'multiplicationFactor.required' => "Multiplication factor cannot be empty",
            'multiplicationFactor.numeric' => "Multiplication factor must be numeric",
            'clientId.required' => "Client id cannot be empty",
        );

        $validator = Validator::make(Input::all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            return Redirect::to('clients/sell/all/altcoins/single/client')
                ->withInput()->withErrors($validator);
        } else {

            $clientId = Input::get('clientId');
            $multiplicationFactor = Input::get('multiplicationFactor');

            if ($multiplicationFactor <= 1) {
                return Redirect::to('clients/sell/all/altcoins/single/client')
                    ->withInput()->withErrors($validator);
            }

            $clientData = ClientsListModel::find($clientId);

            if (count($clientData) > 0) {
                $apiKey = $clientData->apiKey;
                $secretKey = $clientData->secretKey;

                $pauseTrading = $clientData->pauseTrading;
                if (strcasecmp($pauseTrading, 'No') == 0) {
                    $bittrexGeneralUtilities = new BittrexGeneralUtilities($apiKey, $secretKey);
                    $bittrexGeneralUtilities->cancelAllSellLimit();
                    $bittrexGeneralUtilities->createAllSellLimit($multiplicationFactor);
                }

            }

            return Redirect::to('/clients');

        }
    }


    public function pauseAllAccounts()
    {
        $sentryAuthenticator = new SentryAuthenticator();
        $loggedAgentId = $sentryAuthenticator->getLoggedUser()->id;

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

        foreach ($clientsList as $client) {
            $clientRow = ClientsListModel::find($client->id);
            $clientRow->pauseTrading = 'Yes';
            $clientRow->save();
        }
        return Redirect::to('clients/');
    }


    public function buyForAllClientsEdit()
    {
        return View::make('bittrex/clients/utilities/buyforallclients');
    }


    public function buyForAllClients()
    {
        $rules = array(
            'marketName' => 'required',
            'rate' => 'required'
        );

        $validationErrorMessages = array(
            'marketName.required' => "Market name cannot be empty",
            'rate.required' => "Buy price cannot be empty",
        );

        $validator = Validator::make(Input::all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            return Redirect::to('clients/settings/for/all/clients')
                ->withInput()->withErrors($validator);
        } else {

            try {


                $marketName = Input::get('marketName');
                $rate = Input::get('rate');

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

                foreach ($clientsList as $client) {

                    $pauseTrading = $client->pauseTrading;
                    if (strcasecmp($pauseTrading, 'Yes') == 0) {
                        continue;
                    }

                    $data = (object)[];
                    $data->MarketName = $marketName;
                    $data->Ask = $rate;

                    $toBeInvestedCoins [] = $data;

                    $bittrexBuyUtilities = new BittrexBuyUtilities();
                    $newlyInvestedCoins = $bittrexBuyUtilities->invest($client, $toBeInvestedCoins);

                    if (count($newlyInvestedCoins) > 0) {
                        $this->createLogRecordsOfBuying($client, $newlyInvestedCoins);
                    }

                }

            } catch (Exception $exception) {
                Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
            }

            return View::make('bittrex/clients/utilities/buyforallclients');
        }
    }


    public function cancelBuyForAllClientsEdit()
    {
        return View::make('bittrex/clients/utilities/cancelbuyforallclients');
    }


    public function cancelBuyForAllClients()
    {
        $rules = array(
            'marketName' => 'required',
        );

        $validationErrorMessages = array(
            'marketName.required' => "Market name cannot be empty",
        );

        $validator = Validator::make(Input::all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            return Redirect::to('clients/cancel/settings/for/all/clients')
                ->withInput()->withErrors($validator);
        } else {
            try {

                $marketName = Input::get('marketName');

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

                foreach ($clientsList as $client) {

                    $apiKey = $client->apiKey;
                    $secretKey = $client->secretKey;

                    $pauseTrading = $client->pauseTrading;
                    if (strcasecmp($pauseTrading, 'Yes') == 0) {
                        continue;
                    }

                    $bittrexAPIs = new BittrexAPIs($apiKey, $secretKey);
                    $openOrders = $bittrexAPIs->getOpenOrders($marketName);
                    $openOrders = $openOrders->result;
//                    return json_encode($openOrders);
                    if (count($openOrders)) {
                        foreach ($openOrders as $openOrder) {
                            $orderUuid = $openOrder->OrderUuid;
                            if ($orderUuid != null) {
                                $bittrexAPIs->cancel($orderUuid);
                            }
                        }
                    }
                }

            } catch (Exception $exception) {
                Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
            }

            return View::make('bittrex/clients/utilities/cancelbuyforallclients');
        }
    }


    public function sellForAllClientsEdit()
    {
        return View::make('bittrex/clients/utilities/sellforallclients');
    }


    public function sellForAllClients()
    {

        $rules = array(
            'marketName' => 'required',
            'rate' => 'required'
        );

        $validationErrorMessages = array(
            'marketName.required' => "Market name cannot be empty",
            'rate.required' => "Sell price cannot be empty",
        );

        $validator = Validator::make(Input::all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            return Redirect::to('clients/sell/for/all/clients')
                ->withInput()->withErrors($validator);
        } else {

            $marketName = Input::get('marketName');
            $rate = Input::get('rate');

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

            foreach ($clientsList as $client) {

                $pauseTrading = $client->pauseTrading;
                if (strcasecmp($pauseTrading, 'Yes') == 0) {
                    continue;
                }

                $bittrexAPIs = new BittrexAPIs($client->apiKey, $client->secretKey);

                $altcoinsName = str_replace("BTC-", "", $marketName);
                $alcoinBalance = $bittrexAPIs->getBalance($altcoinsName);

                $quantity = $alcoinBalance->Balance;

                if ($quantity * $rate >= $this->bittrexMinimumInvestmentLimits) {
                    Log::info(get_class($this) . '->' . __FUNCTION__ . ' Market Name ' . $marketName . ' $quantity ' . $quantity . ' $rate ' . $rate);
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
                    $this->createLogRecordsOfSelling($client, $marketName, $quantity, $rate);
                } else {
                    Log::info(get_class($this) . '->' . __FUNCTION__ . ' Sell limit order is not placed because it does not meet minimum investments requirement of exchange $marketName ' . $marketName . ' $quantity ' . $quantity . ' rate ' . $rate);
                }

            }

            return View::make('bittrex/clients/utilities/sellforallclients');

        }
    }


    public function cancelSellForAllClientsEdit()
    {
        return View::make('bittrex/clients/utilities/cancelsellforallclients');
    }


    public function cancelSellForAllClients()
    {

        $rules = array(
            'marketName' => 'required',
        );

        $validationErrorMessages = array(
            'marketName.required' => "Market name cannot be empty",
        );

        $validator = Validator::make(Input::all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            return Redirect::to('clients/cancel/sell/for/all/clients')
                ->withInput()->withErrors($validator);
        } else {

            $marketName = Input::get('marketName');

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

            foreach ($clientsList as $client) {

                $apiKey = $client->apiKey;
                $secretKey = $client->secretKey;

                $pauseTrading = $client->pauseTrading;
                if (strcasecmp($pauseTrading, 'Yes') == 0) {
                    continue;
                }

                $bittrexAPIs = new BittrexAPIs($apiKey, $secretKey);

                $openOrders = $bittrexAPIs->getOpenOrders($marketName);
                if (count($openOrders)) {
                    foreach ($openOrders as $openOrder) {
                        $orderUuid = $openOrder->OrderUuid;
                        if ($orderUuid != null) {
                            $bittrexAPIs->cancel($orderUuid);
                        }
                    }
                }
            }
            return View::make('bittrex/clients/utilities/sellforallclients');
        }
    }

    public function createLogRecordsOfBuying($clientData, $data)
    {

        try {
            Log::info('BUY FOR ALL CLIENTS DETAILS');
            Log::info(get_class($this) . '->' . __FUNCTION__ . 'Client name ' . $clientData->fullName);

            foreach ($data as $row) {
                Log::info(get_class($this) . '->' . __FUNCTION__);
                Log::info(get_class($this) . '->' . __FUNCTION__ . 'MarketName ' . $row->MarketName);
                Log::info(get_class($this) . '->' . __FUNCTION__ . 'rate ' . $row->Ask);
                Log::info(get_class($this) . '->' . __FUNCTION__ . 'quantity ' . $row->quantity);
                Log::info(get_class($this) . '->' . __FUNCTION__ . 'btcQuantity ' . $row->btcQuantity);
                Log::info('');
            }

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

    }


    public function createLogRecordsOfSelling($clientData, $marketName, $quantity, $rate)
    {

        try {
            Log::info(get_class($this) . '->' . __FUNCTION__);
            Log::info('SELL FOR ALL CLIENTS DETAILS');
            Log::info(get_class($this) . '->' . __FUNCTION__ . 'Client name ' . $clientData->fullName);
            Log::info(get_class($this) . '->' . __FUNCTION__ . 'MarketName ' . $marketName);
            Log::info(get_class($this) . '->' . __FUNCTION__ . 'rate ' . $rate);
            Log::info(get_class($this) . '->' . __FUNCTION__ . 'quantity ' . $quantity);
            Log::info('');

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

    }


}
