<?php

namespace App\Http\Controllers\BittrexExchange\Clients;

use App\Classes\CurrenciesUtilities;
use App\Models\ClientsListModel;
use Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use LaravelAcl\Authentication\Classes\SentryAuthenticator;
use LaravelAcl\Authentication\Helpers\SentryAuthenticationHelper;


class ClientsListController extends Controller
{

    public function index()
    {
        $currenciesUtilities = new CurrenciesUtilities();
        $USDINRRate = $currenciesUtilities->getUSDINRRateFromMarket();
        $thetherBTCRate = $currenciesUtilities->getTetherBTCRateFromDB();
        $usdBTCRate = $currenciesUtilities->getUSDBTCRateFromDB();
        $INRBTCRate = $usdBTCRate * $USDINRRate;

        $sentryAuthenticationHelper = new SentryAuthenticationHelper();
        $isSuperAdminPermission = $sentryAuthenticationHelper->hasPermission(array('_superadmin'));
        $isAgentPermission = $sentryAuthenticationHelper->hasPermission(array('_agent'));

        if($isSuperAdminPermission){
            $clientslist = ClientsListModel::all();
        }elseif($isAgentPermission){
            $sentryAuthenticator = new SentryAuthenticator();
            $loggedAgentId = $sentryAuthenticator->getLoggedUser()->id;
            $clientslist = ClientsListModel::where('agentId', '=', $loggedAgentId)->get();
        }

        return View::make('bittrex/clients/index', compact('clientslist','INRBTCRate','thetherBTCRate','usdBTCRate'));
    }

    public function create()
    {
        return View::make('bittrex/clients/create', compact('error'));

    }


    public function store(Request $request)
    {
        $rules = array(
            'fullName' => 'required',
            'emailAddress' => 'required',
            'mobileNumber' => 'required',
            'apiKey' => 'required',
            'secretKey' => 'required',
            'referrerFullName' => 'required',
            'referrerMobileNumber' => 'required',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('/clients/create')
                ->withErrors($validator)->withInput();
        } else {

            $fullName = Input::get('fullName');
            $emailAddress = Input::get('emailAddress');
            $mobileNumber = Input::get('mobileNumber');
            $apiKey = Input::get('apiKey');
            $secretKey = Input::get('secretKey');
            $referrerFullName = Input::get('referrerFullName');
            $referrerMobileNumber = Input::get('referrerMobileNumber');
            $remark = Input::get('remark');

            $clientsList = new ClientsListModel();
            $clientsList->fullName = $fullName;
            $clientsList->emailAddress = $emailAddress;
            $clientsList->mobileNumber = $mobileNumber;
            $clientsList->originalMobileNumber = $mobileNumber;
            $clientsList->apiKey = $apiKey;
            $clientsList->secretKey = $secretKey;
            $clientsList->referrerFullName = $referrerFullName;
            $clientsList->referrerMobileNumber = $referrerMobileNumber;
            $clientsList->remark = $remark;
            $clientslists = ClientsListModel::where('apiKey', '=', $apiKey)->get();

            if (count($clientslists) > 0) {
                $error = 'API/Secret key already exist';
            } else {
                if ($clientsList->save()) {
                    return Redirect::to('/clients');
                } else {
                    $error = 'Error adding client';
                }

            }
            return View::make('bittrex/clients/create', compact('error'));
        }

    }

    public function edit($id)
    {
        $clientList = ClientsListModel::find($id);
        $pauseTrading = array(
            0 => 'Select',
            'Yes' => 'Yes',
            'No' => 'No'
        );

        return View::make('bittrex/clients/edit', compact('clientList', 'pauseTrading'));
    }

    public function update(Request $request, $id)
    {

        $rules = array(
            'fullName' => 'required',
            'emailAddress' => 'required',
            'mobileNumber' => 'required',
            'apiKey' => 'required',
            'secretKey' => 'required',
            'pauseTrading' => 'required|not_in:0',
        );

        $id = Input::get('clientListId');
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            // get the error messages from the validator
            return Redirect::to('clients/' . $id . '/edit')
                ->withErrors($validator)->withInput();
        } else {

            $fullName = Input::get('fullName');
            $emailAddress = Input::get('emailAddress');
            $mobileNumber = Input::get('mobileNumber');
            $apiKey = Input::get('apiKey');
            $secretKey = Input::get('secretKey');
            $pauseTrading = Input::get('pauseTrading');
            $remark = Input::get('remark');

            $clientslist = ClientsListModel::find($id);
            $clientslist->fullName = $fullName;
            $clientslist->emailAddress = $emailAddress;
            $clientslist->mobileNumber = $mobileNumber;
            $clientslist->apiKey = $apiKey;
            $clientslist->secretKey = $secretKey;
            $clientslist->pauseTrading = $pauseTrading;
            $clientslist->remark = $remark;

            if ($clientslist->save()) {
                return Redirect::to('clients/');
            } else {
                $error = 'Error updating client';
            }

            $clientList = ClientsListModel::find($id);
            return View::make('bittrex/clients/edit', compact('error', 'clientList', 'error'));

        }

    }

}
