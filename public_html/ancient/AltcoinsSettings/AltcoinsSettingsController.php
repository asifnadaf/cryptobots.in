<?php

namespace App\Http\Controllers\BittrexExchange;

use App\Models\AltcoinsSettingsModel;
use Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;


class AltcoinsSettingsController extends Controller
{

    public function index()
    {

        $altcoinsSettingsData = AltcoinsSettingsModel::orderBy('marketName', 'asc')->get();
        return View::make('bittrex/altcoinssettings/index', compact('altcoinsSettingsData'));
    }

    public function create()
    {

        $pauseTradingOptions = array(
            'Yes' => 'Yes',
            'No' => 'No'
        );
        return View::make('bittrex/altcoinssettings/create', compact('pauseTradingOptions'));

    }


    public function store(Request $request)
    {

        $rules = array(
            'exchangeName' => 'required',
            'marketName' => 'required',
            'isBuyingPaused' => 'required|not_in:0',
            'isSellingOnResistancePaused' => 'required|not_in:0',
            'isSellingAt2XPaused' => 'required|not_in:0',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('/altcoinssettings/create')
                ->withErrors($validator)->withInput();
        } else {

            $exchangeName = Input::get('exchangeName');
            $marketName = Input::get('marketName');
            $isBuyingPaused = Input::get('isBuyingPaused');
            $isSellingOnResistancePaused = Input::get('isSellingOnResistancePaused');
            $isSellingAt2XPaused = Input::get('isSellingAt2XPaused');

            $newAltcoin = new AltcoinsSettingsModel;
            $newAltcoin->exchangeName = $exchangeName;
            $newAltcoin->marketName = $marketName;
            $newAltcoin->isBuyingPaused = $isBuyingPaused;
            $newAltcoin->isSellingOnResistancePaused = $isSellingOnResistancePaused;
            $newAltcoin->isSellingAt2XPaused = $isSellingAt2XPaused;

            $altcoin = AltcoinsSettingsModel::where('MarketName', '=', $marketName)->get();

            if (count($altcoin) > 0) {
                $error = 'Altcoin name is already added';
            } else {

                if ($newAltcoin->save()) {
                    return Redirect::to('/altcoinssettings');
                } else {
                    $error = 'Error adding market name';
                }

            }
            return View::make('bittrex/altcoinssettings/create', compact('error'));
        }
    }


    public function edit($id)
    {
        $altcoinData = AltcoinsSettingsModel::find($id);
        $pauseTradingOptions = array(
            'Yes' => 'Yes',
            'No' => 'No'
        );
        return View::make('bittrex/altcoinssettings/edit', compact('altcoinData', 'pauseTradingOptions'));

    }

    public function update(Request $request, $id)
    {

        $rules = array(
            'exchangeName' => 'required',
            'marketName' => 'required',
            'isBuyingPaused' => 'required|not_in:0',
            'isSellingOnResistancePaused' => 'required|not_in:0',
            'isSellingAt2XPaused' => 'required|not_in:0',
        );

        $id = Input::get('altcoinDataId');

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('altcoinssettings/' . $id . '/edit')
                ->withErrors($validator)->withInput();
        } else {

            $exchangeName = Input::get('exchangeName');
            $marketName = Input::get('marketName');
            $isBuyingPaused = Input::get('isBuyingPaused');
            $isSellingOnResistancePaused = Input::get('isSellingOnResistancePaused');
            $isSellingAt2XPaused = Input::get('isSellingAt2XPaused');

            $editAltcoin = AltcoinsSettingsModel::find($id);
            $editAltcoin->exchangeName = $exchangeName;
            $editAltcoin->marketName = $marketName;
            $editAltcoin->isBuyingPaused = $isBuyingPaused;
            $editAltcoin->isSellingOnResistancePaused = $isSellingOnResistancePaused;
            $editAltcoin->isSellingAt2XPaused = $isSellingAt2XPaused;

            if ($editAltcoin->save()) {
                return Redirect::to('/altcoinssettings');
            } else {
                $error = 'Error updating altcoin';
            }

            $altcoinData = AltcoinsSettingsModel::find($id);
            $pauseTradingOptions = array(
                'Yes' => 'Yes',
                'No' => 'No'
            );
            return View::make('bittrex/altcoinssettings/edit', compact('error', 'altcoinData', 'pauseTradingOptions'));

        }
    }

}
