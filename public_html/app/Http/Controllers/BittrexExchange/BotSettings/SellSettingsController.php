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


class SellSettingsController extends Controller
{

    public function index()
    {
        $settings = BotSettingsModel::first();
        return View::make('bittrex/arbitragesettings/sell/index', compact('settings'));
    }

    public function edit($id)
    {
        $setting = BotSettingsModel::find($id);
        return View::make('bittrex/arbitragesettings/sell/edit', compact('setting', 'pauseBuyAtSupportBot', 'pauseSellOnResistancePriceBot', 'pauseUpdateSellLimitOrderBookToXTimesBot'));
    }

    public function update(Request $request, $id)
    {
        $rules = array(
            'pumpFactor' => 'required | numeric | min:1',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('sellsetting/' . $id . '/edit')
                ->withErrors($validator)->withInput();
        } else {

            $pumpFactor = Input::get('pumpFactor');

            $setting = BotSettingsModel::find($id);

            $setting->pumpFactor = $pumpFactor;

            if ($setting->save()) {
                return Redirect::to('/sellsetting');
            } else {
                $error = 'Error updating settings';
            }

            $setting = BotSettingsModel::find($id);
            return View::make('sellsetting/' . $id . '/edit', compact('error', 'setting'));

        }

    }


}
