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


class BuySettingsController extends Controller
{

    public function index()
    {
        $settings = BotSettingsModel::first();
        return View::make('bittrex/arbitragesettings/settings/index', compact('settings'));
    }

    public function edit($id)
    {
        $setting = BotSettingsModel::find($id);
        return View::make('bittrex/arbitragesettings/settings/edit', compact('setting', 'pauseBuyAtSupportBot', 'pauseSellOnResistancePriceBot', 'pauseUpdateSellLimitOrderBookToXTimesBot'));
    }

    public function update(Request $request, $id)
    {
        $rules = array(
            'supportAndEqualOddsRatio' => 'required | numeric',
            'maximumNumberOfDiversification' => 'required | numeric',
            'minimumVolumeOfBaseCurrencyBTC' => 'required | numeric',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('buysetting/' . $id . '/edit')
                ->withErrors($validator)->withInput();
        } else {

            $supportAndEqualOddsRatio = Input::get('supportAndEqualOddsRatio');
            $maximumNumberOfDiversification = Input::get('maximumNumberOfDiversification');
            $minimumVolumeOfBaseCurrencyBTC = Input::get('minimumVolumeOfBaseCurrencyBTC');

            $setting = BotSettingsModel::find($id);

            $setting->supportAndEqualOddsRatio = $supportAndEqualOddsRatio;
            $setting->maximumNumberOfDiversification = $maximumNumberOfDiversification;
            $setting->minimumVolumeOfBaseCurrencyBTC = $minimumVolumeOfBaseCurrencyBTC;

            if ($setting->save()) {
                return Redirect::to('/buysetting');
            } else {
                $error = 'Error updating settings';
            }

            $setting = BotSettingsModel::find($id);
            return View::make('buysetting/' . $id . '/edit', compact('error', 'setting'));

        }

    }


}
