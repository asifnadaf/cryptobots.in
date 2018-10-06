<?php

namespace App\Http\Controllers\BittrexExchange\Clients;

use App\Classes\BittrexAccountUtilities;
use App\Models\PaymentReceiptModel;
use App\Models\ClientsListModel;
use Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;


class PaymentReceiptController extends Controller
{

    public function index($clientId)
    {
        $clientData = ClientsListModel::find($clientId);

        $apiKey = $clientData->apiKey;
        $secretKey = $clientData->secretKey;

        $bittrexAccountUtilities = new BittrexAccountUtilities($apiKey,$secretKey);
        $accountBalance = $bittrexAccountUtilities->getUSDTAndBTCBalance();
        $accountError = null;;

        $paymentReceipts = PaymentReceiptModel::where('clientId', '=', $clientId)->get();
        return View::make('bittrex/clients/paymentreceipt/index', compact('paymentReceipts', 'clientData','accountBalance','accountError'));
    }

    public function create($clientId)
    {
        $clientList = ClientsListModel::find($clientId);
        return View::make('bittrex/clients/paymentreceipt/create', compact('error', 'clientList'));
    }


    public function store()
    {

        $rules = array(
            'clientId' => 'required',
            'clientFullName' => 'required',
            'paymentDate' => 'required',
            'btcValue' => 'required | numeric',
            'paymentReference' => 'required',
        );

        $validator = Validator::make(Input::all(), $rules);
        $clientId = Input::get('clientId');

        if ($validator->fails()) {
            return Redirect::to('clients/payment/' . $clientId . '/create')// /clients/payment/{id?}/create
            ->withErrors($validator)->withInput();
        } else {

            $clientFullName = Input::get('clientFullName');
            $btcValue = Input::get('btcValue');
            $paymentDate = Input::get('paymentDate');
            $paymentReference = Input::get('paymentReference');
            $remark = Input::get('remark');

            $paymentReceipt = new PaymentReceiptModel();
            $paymentReceipt->clientId = $clientId;
            $paymentReceipt->clientFullName = $clientFullName;
            $paymentReceipt->btcValue = $btcValue;
            $paymentReceipt->paymentDate = $paymentDate;
            $paymentReceipt->paymentReference = $paymentReference;
            $paymentReceipt->remark = $remark;

            if ($paymentReceipt->save()) {
                return Redirect::to('clients/payment/' . $clientId);
            } else {
                $error = 'Error updating payment';
            }
            $clientList = ClientsListModel::find($clientId);
            return View::make('bittrex/clients/paymentreceipt/create', compact('error', 'clientList'));

        }

    }

    public function edit($clientId, $paymentId)
    {
        $paymentReceipt = PaymentReceiptModel::where('clientId', '=', $clientId)->where('id', '=', $paymentId)->first();
        return View::make('bittrex/clients/paymentreceipt/edit', compact('paymentReceipt'));
    }

    public function update(Request $request)
    {
        $rules = array(
            'clientId' => 'required',
            'clientFullName' => 'required',
            'paymentDate' => 'required',
            'btcValue' => 'required | numeric',
            'paymentReference' => 'required',
        );

        $clientId = Input::get('clientId');
        $paymentId = Input::get('id');
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('clients/payment/' . $clientId . '/' . $paymentId . '/edit')
                ->withErrors($validator)->withInput();
        } else {

            $clientFullName = Input::get('clientFullName');
            $btcValue = Input::get('btcValue');
            $paymentDate = Input::get('paymentDate');
            $paymentReference = Input::get('paymentReference');
            $remark = Input::get('remark');

            $paymentReceipt = PaymentReceiptModel::find($paymentId);
            $paymentReceipt->clientId = $clientId;
            $paymentReceipt->clientFullName = $clientFullName;
            $paymentReceipt->btcValue = $btcValue;
            $paymentReceipt->paymentDate = $paymentDate;
            $paymentReceipt->paymentReference = $paymentReference;
            $paymentReceipt->remark = $remark;

            if ($paymentReceipt->save()) {
                Log::info(get_class($this).'->'.__FUNCTION__ . ' Record edited ' + serialize($paymentReceipt));
                return Redirect::to('clients/payment/' . $clientId);
            } else {
                $error = 'Error updating payment';
            }

            $paymentReceipt = PaymentReceipt::find($clientId);
            return View::make('bittrex/clients/paymentreceipt/edit', compact('paymentReceipt','error'));

        }
    }

}
