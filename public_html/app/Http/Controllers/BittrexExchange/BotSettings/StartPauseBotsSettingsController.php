<?php

namespace App\Http\Controllers\BittrexExchange\BotSettings;

use App\Models\BotSettingsModel;
use Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;


class StartPauseBotsSettingsController extends Controller
{

    public function index()
    {
        $settings = BotSettingsModel::first();
        return View::make('bittrex/arbitragesettings/startpausebot/index', compact('settings'));
    }

    public function edit($id)
    {
        $setting = BotSettingsModel::find($id);

        $pauseBuyAtSupportBot = array(
            0 => 'Select',
            'Yes' => 'Yes',
            'No' => 'No'
        );

        $pauseSellOnResistancePriceBot = array(
            0 => 'Select',
            'Yes' => 'Yes',
            'No' => 'No'
        );

        $pauseUpdateSellLimitOrderBookToXTimesBot = array(
            0 => 'Select',
            'Yes' => 'Yes',
            'No' => 'No'
        );


        return View::make('bittrex/arbitragesettings/startpausebot/edit', compact('setting', 'pauseBuyAtSupportBot', 'pauseSellOnResistancePriceBot', 'pauseUpdateSellLimitOrderBookToXTimesBot'));
    }

    public function update(Request $request, $id)
    {
        $rules = array(
            'pauseBuyAtSupportBot' => 'required|not_in:0',
            'pauseSellOnResistancePriceBot' => 'required|not_in:0',
            'pauseUpdateSellLimitOrderBookToXTimesBot' => 'required|not_in:0'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('startpausebotsetting/' . $id . '/edit')
                ->withErrors($validator)->withInput();
        } else {

            $pauseBuyAtSupportBot = Input::get('pauseBuyAtSupportBot');
            $pauseSellOnResistancePriceBot = Input::get('pauseSellOnResistancePriceBot');
            $pauseUpdateSellLimitOrderBookToXTimesBot = Input::get('pauseUpdateSellLimitOrderBookToXTimesBot');

            $setting = BotSettingsModel::find($id);

            $setting->pauseBuyAtSupportBot = $pauseBuyAtSupportBot;
            $setting->pauseSellOnResistancePriceBot = $pauseSellOnResistancePriceBot;
            $setting->pauseUpdateSellLimitOrderBookToXTimesBot = $pauseUpdateSellLimitOrderBookToXTimesBot;

            if ($setting->save()) {
                return Redirect::to('/startpausebotsetting');
            } else {
                $error = 'Error updating settings';
            }

            $setting = BotSettingsModel::find($id);
            return View::make('startpausebotsetting/' . $id . '/edit', compact('error', 'setting'));

        }

    }


}
