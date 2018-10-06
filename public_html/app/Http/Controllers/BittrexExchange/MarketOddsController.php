<?php

namespace App\Http\Controllers\BittrexExchange;

use App\Models\MarketOddsModel;
use App\Models\BotSettingsModel;
use Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;


class MarketOddsController extends Controller
{

    public function index()
    {
        $botSettingRow = BotSettingsModel::first();
        $minimumVolumeOfBaseCurrencyBTC = $botSettingRow->minimumVolumeOfBaseCurrencyBTC;
        $supportAndEqualOddsRatio = $botSettingRow->supportAndEqualOddsRatio;

        $USDTBTCPrices = MarketOddsModel::where('marketName', '=', 'USDT-BTC')->get();
        $supportPricesCurrencies = MarketOddsModel::whereRaw('Ask <=supportPrice')->where('supportAndEqualOddsRatio', '>=', $supportAndEqualOddsRatio)->where('BaseVolume', '>=', $minimumVolumeOfBaseCurrencyBTC)->orderBy('supportNLastPercentageDifference', 'asc')->get();
        $betweenPricesCurrencies = MarketOddsModel::whereRaw('Ask >supportPrice AND Bid <equalOddsPrice')->where('supportAndEqualOddsRatio', '>=', $supportAndEqualOddsRatio)->where('BaseVolume', '>=', $minimumVolumeOfBaseCurrencyBTC)->orderBy('supportNLastPercentageDifference', 'asc')->get();
        $equalOddsPricesCurrencies = MarketOddsModel::whereRaw('Bid >=equalOddsPrice')->where('supportAndEqualOddsRatio', '>=', $supportAndEqualOddsRatio)->where('BaseVolume', '>=', $minimumVolumeOfBaseCurrencyBTC)->orderBy('supportNLastPercentageDifference', 'desc')->get();
        $allCurrencies = MarketOddsModel::orderBy('supportNLastPercentageDifference', 'desc')->get();
        return View::make('bittrex/odds/index', compact('USDTBTCPrices', 'supportPricesCurrencies', 'equalOddsPricesCurrencies', 'betweenPricesCurrencies', 'allCurrencies', 'minimumVolumeOfBaseCurrencyBTC', 'supportAndEqualOddsRatio'));
    }

    public function create()
    {
        $pauseTradingOptions = array(
            'Yes' => 'Yes',
            'No' => 'No'
        );
        return View::make('bittrex/odds/create', compact('pauseTradingOptions'));

    }


    public function store(Request $request)
    {
        $rules = array(
            'exchangeName' => 'required',
            'marketName' => 'required',
            'isBuyingPaused' => 'required|not_in:0',
            'isSellingOnEqualOddsPaused' => 'required|not_in:0',
            'isSellingAt2XPaused' => 'required|not_in:0',
            'isSellingOnResistancePaused' => 'required|not_in:0',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('/odds/create')
                ->withErrors($validator)->withInput();
        } else {

            $exchangeName = Input::get('exchangeName');
            $marketName = Input::get('marketName');
            $isBuyingPaused = Input::get('isBuyingPaused');
            $isSellingOnEqualOddsPaused = Input::get('isSellingOnEqualOddsPaused');
            $isSellingAt2XPaused = Input::get('isSellingAt2XPaused');
            $isSellingOnResistancePaused = Input::get('isSellingOnResistancePaused');

            $newAltcoin = new MarketOddsModel;
            $newAltcoin->exchangeName = $exchangeName;
            $newAltcoin->marketName = $marketName;
            $newAltcoin->isBuyingPaused = $isBuyingPaused;
            $newAltcoin->isSellingOnEqualOddsPaused = $isSellingOnEqualOddsPaused;
            $newAltcoin->isSellingAt2XPaused = $isSellingAt2XPaused;
            $newAltcoin->isSellingOnResistancePaused = $isSellingOnResistancePaused;

            $altcoin = MarketOddsModel::where('MarketName', '=', $marketName)->get();

            if (count($altcoin) > 0) {
                $error = 'Altcoin name is already added';
            } else {

                if ($newAltcoin->save()) {
                    return Redirect::to('/odds');
                } else {
                    $error = 'Error adding market name';
                }

            }
            return View::make('bittrex/odds/create', compact('error'));
        }
    }


    public function edit($id)
    {
        $altcoinData = MarketOddsModel::find($id);
        $pauseTradingOptions = array(
            'Yes' => 'Yes',
            'No' => 'No'
        );

        return View::make('bittrex/odds/edit', compact('altcoinData', 'pauseTradingOptions'));

    }

    public function update(Request $request, $id)
    {
        $rules = array(
            'exchangeName' => 'required',
            'marketName' => 'required',
            'isBuyingPaused' => 'required|not_in:0',
            'isSellingOnEqualOddsPaused' => 'required|not_in:0',
            'isSellingAt2XPaused' => 'required|not_in:0',
            'isSellingOnResistancePaused' => 'required|not_in:0',
        );

        $id = Input::get('altcoinDataId');

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('odds/' . $id . '/edit')
                ->withErrors($validator)->withInput();
        } else {

            $exchangeName = Input::get('exchangeName');
            $marketName = Input::get('marketName');
            $isBuyingPaused = Input::get('isBuyingPaused');
            $isSellingOnEqualOddsPaused = Input::get('isSellingOnEqualOddsPaused');
            $isSellingAt2XPaused = Input::get('isSellingAt2XPaused');
            $isSellingOnResistancePaused = Input::get('isSellingOnResistancePaused');

            $editAltcoin = MarketOddsModel::find($id);
            $editAltcoin->exchangeName = $exchangeName;
            $editAltcoin->marketName = $marketName;
            $editAltcoin->isBuyingPaused = $isBuyingPaused;
            $editAltcoin->isSellingOnEqualOddsPaused = $isSellingOnEqualOddsPaused;
            $editAltcoin->isSellingAt2XPaused = $isSellingAt2XPaused;
            $editAltcoin->isSellingOnResistancePaused = $isSellingOnResistancePaused;

            if ($editAltcoin->save()) {
                return Redirect::to('/odds');
            } else {
                $error = 'Error updating altcoin';
            }

            $altcoinData = MarketOddsModel::find($id);
            $pauseTradingOptions = array(
                'Yes' => 'Yes',
                'No' => 'No'
            );
            return View::make('bittrex/odds/edit', compact('error', 'altcoinData', 'pauseTradingOptions'));

        }
    }

}
