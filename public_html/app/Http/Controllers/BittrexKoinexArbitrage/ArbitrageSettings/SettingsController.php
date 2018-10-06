<?php

namespace App\Http\Controllers\BittrexKoinexArbitrage\ArbitrageSettings;

use App\Models\BittrexKoinexArbitrageSettingsModel;
use Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;


class SettingsController extends Controller
{

    public function index()
    {
        $settings = BittrexKoinexArbitrageSettingsModel::first();
        return View::make('bittrex/bittrexkoinexarbitrage/arbitragesettings/settings/index', compact('settings'));
    }

    public function edit($id)
    {
        $lookAtKoinexTickerVolumeUpdateTimestampOptions = array(
            0 => 'Select',
            'Yes' => 'Yes',
            'No' => 'No'
        );

        $setting = BittrexKoinexArbitrageSettingsModel::find($id);
        return View::make('bittrex/bittrexkoinexarbitrage/arbitragesettings/settings/edit', compact('setting','lookAtKoinexTickerVolumeUpdateTimestampOptions'));
    }

    public function update(Request $request, $id)
    {
        $rules = array(
            'minimumTradeSize' => 'required | numeric',
            'maximumTradeSize' => 'required | numeric',
            'minimumGrossPercentGain' => 'required | numeric',
            'lookAtKoinexTickerVolumeUpdateTimestamp' => 'required|not_in:0',
            'bittrexBidAboveLowestAskByPercent' => 'required | numeric',
            'bittrexAskBelowHighestBidByPercent' => 'required | numeric',
            'koinexBidAboveLowestAskByPercent' => 'required | numeric',
            'koinexAskBelowHighestBidByPercent' => 'required | numeric',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('kbas/' . $id . '/edit')
                ->withErrors($validator)->withInput();
        } else {

            $minimumTradeSize = Input::get('minimumTradeSize');
            $maximumTradeSize = Input::get('maximumTradeSize');
            $minimumGrossPercentGain = Input::get('minimumGrossPercentGain');
            $lookAtKoinexTickerVolumeUpdateTimestamp = Input::get('lookAtKoinexTickerVolumeUpdateTimestamp');
            $bittrexBidAboveLowestAskByPercent = Input::get('bittrexBidAboveLowestAskByPercent');
            $bittrexAskBelowHighestBidByPercent = Input::get('bittrexAskBelowHighestBidByPercent');
            $koinexBidAboveLowestAskByPercent = Input::get('koinexBidAboveLowestAskByPercent');
            $koinexAskBelowHighestBidByPercent = Input::get('koinexAskBelowHighestBidByPercent');

            $setting = BittrexKoinexArbitrageSettingsModel::find($id);

            $setting->minimumTradeSize = $minimumTradeSize;
            $setting->maximumTradeSize = $maximumTradeSize;
            $setting->minimumGrossPercentGain = $minimumGrossPercentGain;
            $setting->lookAtKoinexTickerVolumeUpdateTimestamp = $lookAtKoinexTickerVolumeUpdateTimestamp;
            $setting->bittrexBidAboveLowestAskByPercent = $bittrexBidAboveLowestAskByPercent;
            $setting->bittrexAskBelowHighestBidByPercent = $bittrexAskBelowHighestBidByPercent;
            $setting->koinexBidAboveLowestAskByPercent = $koinexBidAboveLowestAskByPercent;
            $setting->koinexAskBelowHighestBidByPercent = $koinexAskBelowHighestBidByPercent;

            if ($setting->save()) {
                return Redirect::to('/kbas');
            } else {
                $error = 'Error updating settings';
            }

            $setting = BittrexKoinexArbitrageSettingsModel::find($id);
            return View::make('kbis/' . $id . '/edit', compact('error', 'setting'));

        }

    }


}
