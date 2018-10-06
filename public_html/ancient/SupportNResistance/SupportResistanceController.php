<?php

namespace App\Http\Controllers\BittrexExchange;

use App\Models\SupportResistanceModel;
use App\Models\BotSettingsModel;
use Log;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;


class SupportResistanceController extends Controller
{

    public function index()
    {

        $botSettingRow = BotSettingsModel::first();
        $minimumVolumeOfBaseCurrencyBTC = $botSettingRow->minimumVolumeOfBaseCurrencyBTC;
        $minimumSupportResistancePercentageDifference = $botSettingRow->minimumSupportResistancePercentageDifference;

        $pausedCurrencies = SupportResistanceModel::where('pauseTrading', '=', 'Yes')->where('averageSupportAndAverageResistancePercentageDifference', '>=', $minimumSupportResistancePercentageDifference)->where('BaseVolume', '>=', $minimumVolumeOfBaseCurrencyBTC)->orderBy('avgSupportNLastdifference', 'asc')->get();
        $supportPricesCurrencies = SupportResistanceModel::whereRaw('Last <=averageSupportPrice')->where('averageSupportAndAverageResistancePercentageDifference', '>=', $minimumSupportResistancePercentageDifference)->where('BaseVolume', '>=', $minimumVolumeOfBaseCurrencyBTC)->where('pauseTrading', '=', 'No')->where('BaseVolume', '>=', $minimumVolumeOfBaseCurrencyBTC)->where('averageSupportAndAverageResistancePercentageDifference', '>=', $minimumSupportResistancePercentageDifference)->orderBy('avgSupportNLastdifference', 'asc')->get();
        $resistancePricesCurrencies = SupportResistanceModel::whereRaw('Last >=averageResistancePrice')->where('averageSupportAndAverageResistancePercentageDifference', '>=', $minimumSupportResistancePercentageDifference)->where('BaseVolume', '>=', $minimumVolumeOfBaseCurrencyBTC)->where('pauseTrading', '=', 'No')->orderBy('avgSupportNLastdifference', 'desc')->get();
        $betweenPricesCurrencies = SupportResistanceModel::whereRaw('Last >=averageSupportPrice AND Last <=averageResistancePrice')->where('averageSupportAndAverageResistancePercentageDifference', '>=', $minimumSupportResistancePercentageDifference)->where('BaseVolume', '>=', $minimumVolumeOfBaseCurrencyBTC)->where('pauseTrading', '=', 'No')->orderBy('avgSupportNLastdifference', 'asc')->get();

        $supportAndResistancePrices = null;
        foreach ($pausedCurrencies as $row){
            $row->currentPriceBelowSupportPrice = false;
            $row->currentPriceAboveResistancePrice = false;
            $supportAndResistancePrices[] = $row;
        }

        foreach ($supportPricesCurrencies as $row) {
            $row->currentPriceBelowSupportPrice = true;
            $row->currentPriceAboveResistancePrice = false;
            $supportAndResistancePrices[] = $row;
        }

        foreach ($resistancePricesCurrencies as $row){
            $row->currentPriceBelowSupportPrice = false;
            $row->currentPriceAboveResistancePrice = true;
            $supportAndResistancePrices[] = $row;
        }

        foreach ($betweenPricesCurrencies as $row){
            $row->currentPriceBelowSupportPrice = false;
            $row->currentPriceAboveResistancePrice = false;
            $supportAndResistancePrices[] = $row;
        }

        return View::make('bittrex/support/index', compact('supportAndResistancePrices'));
    }

    public function create()
    {
        $pauseTrading = array(
            'Yes' => 'Yes',
            'No' => 'No'
        );
        return View::make('bittrex/support/create', compact('pauseTrading'));

    }


    public function store(Request $request)
    {
        $rules = array(
            'exchangeName' => 'required',
            'marketName' => 'required',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('/support/create')
                ->withErrors($validator)->withInput();
        } else {

            $exchangeName = Input::get('exchangeName');
            $marketName = Input::get('marketName');

            $supportPrice = new SupportResistanceModel;
            $supportPrice->exchangeName = $exchangeName;
            $supportPrice->marketName = $marketName;

            $supportPrices = SupportResistanceModel::where('MarketName', '=', $marketName)->get();

            if (count($supportPrices) > 0) {
                $error = 'Market name is already added';
            } else {

                if ($supportPrice->save()) {
                    return Redirect::to('/support');
                } else {
                    $error = 'Error adding market name';
                }

            }
            return View::make('bittrex/support/create', compact('error'));
        }
    }


    public function edit($id)
    {
        $supportPrice = SupportResistanceModel::find($id);
        $pauseTrading = array(
            'Yes' => 'Yes',
            'No' => 'No'
        );
        if($supportPrice->reasonForPauseDate==null){
            $supportPrice->reasonForPauseDate = Carbon::now();
        }

        return View::make('bittrex/support/edit', compact('supportPrice', 'pauseTrading'));

    }

    public function update(Request $request, $id)
    {
        $rules = array(
            'pauseTrading' => 'required|not_in:0',
            'reasonForPause' => 'required',
        );

        $id = Input::get('supportPriceId');

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            // get the error messages from the validator
            return Redirect::to('support/' . $id . '/edit')
                ->withErrors($validator)->withInput();
        } else {

            $pauseTrading = Input::get('pauseTrading');
            $reasonForPause = Input::get('reasonForPause');
            $reasonForPauseDate = Carbon::now();

            $supportPrice = SupportResistanceModel::find($id);
            $supportPrice->pauseTrading = $pauseTrading;
            $supportPrice->reasonForPause = $reasonForPause;
            $supportPrice->reasonForPauseDate = $reasonForPauseDate;

            if ($supportPrice->save()) {
                return Redirect::to('/support');
            } else {
                $error = 'Error updating support price';
            }

            $supportPrice = SupportResistanceModel::find($id);
            $pauseTrading = array(
                'Yes' => 'Yes',
                'No' => 'No'
            );
            return View::make('bittrex/support/edit', compact('error', 'supportPrice', 'pauseTrading'));

        }
    }

}
