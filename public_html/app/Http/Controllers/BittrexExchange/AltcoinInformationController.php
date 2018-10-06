<?php

namespace App\Http\Controllers\BittrexExchange;

use App\Http\Controllers\Controller;
use App\Models\MarketOddsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

class AltcoinInformationController extends Controller
{
    public function index()
    {

        $url = "https://api.coinmarketcap.com/v1/ticker/?limit=10000";
        $coinMarketTickers = file_get_contents($url);
        $coinMarketTickers = json_decode($coinMarketTickers, true);

        $BTCPrefix = 'BTC-';
        $tickers = [];
        $MarketOddsModelData = MarketOddsModel::all();

        foreach ($MarketOddsModelData as $marketOddsModelDataRow) {

            $marketName = $marketOddsModelDataRow['MarketName'];
            $altcoinSymbol = str_replace($BTCPrefix, "", $marketName);
            $altcoinSymbol = str_replace('USDT-', "", $altcoinSymbol);

            if(strcasecmp($altcoinSymbol,'BCC')==0){
                $altcoinSymbol = 'BCH';
            }

            if(strcasecmp($altcoinSymbol,'1ST')==0){
                $altcoinSymbol = 'FRST';
            }
            if(strcasecmp($altcoinSymbol,'BTC')==0){
                $altcoinSymbol = 'BTC';
            }

            foreach ($coinMarketTickers as $coinMarketTicker) {
                if(strcasecmp($altcoinSymbol,$coinMarketTicker['symbol'])==0){
                    $dataRow = MarketOddsModel::where('MarketName', '=', $marketName)->first();
                    $dataRow =  $dataRow->toArray();
                    $tickers[] = array_merge($coinMarketTicker,$dataRow);
                }
            }
        }

        usort($tickers, function ($a, $b) {
            return $a['rank'] <=> $b['rank'];
        });

        return View::make('bittrex/altcoininfo/index', compact('tickers'));

    }


    public function edit($id)
    {
        $ticker = MarketOddsModel::find($id);
        return View::make('bittrex/altcoininfo/edit', compact('ticker'));
    }


    public function update(Request $request, $id)
    {
        $rules = array(
            'remark' => 'required',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            // get the error messages from the validator
            return Redirect::to('altcoininfo/' . $id.'/edit')
                ->withErrors($validator)->withInput();
        } else {

            $remark = Input::get('remark');

            $altcoinInformation = MarketOddsModel::find($id);
            $altcoinInformation->remark = $remark;

            if ($altcoinInformation->save()) {
                return Redirect::to('altcoininfo');
            } else {
                $error = 'Error updating altcoin information';
            }

            $ticker = MarketOddsModel::find($id);
            return View::make('bittrex/altcoininfo/edit', compact('error', 'ticker'));
        }
    }

    public function loadCoins()
    {
        $url = "https://api.coinmarketcap.com/v1/ticker/?limit=10000";
        $coinMarketTickers = file_get_contents($url);
        $coinMarketTickers = json_decode($coinMarketTickers, true);

        $BTCPrefix = 'BTC-';

        $MarketOddsModelData = MarketOddsModel::all();

        foreach ($MarketOddsModelData as $marketOddsModelDataRow) {

            $marketName = $marketOddsModelDataRow['MarketName'];
            $altcoinSymbol = str_replace($BTCPrefix, "", $marketName);
            $altcoinSymbol = str_replace('USDT-', "", $altcoinSymbol);

            if(strcasecmp($altcoinSymbol,'BCC')==0){
                $altcoinSymbol = 'BCH';
            }

            if(strcasecmp($altcoinSymbol,'1ST')==0){
                $altcoinSymbol = 'FRST';
            }
            if(strcasecmp($altcoinSymbol,'BTC')==0){
                $altcoinSymbol = 'BTC';
            }

            foreach ($coinMarketTickers as $coinMarketTicker) {
                if(strcasecmp($altcoinSymbol,$coinMarketTicker['symbol'])==0){
                    $dataRow = MarketOddsModel::where('MarketName', '=', $marketName)->first();
                    $dataRow->coinMarketCapCoinId = $coinMarketTicker['id'];
                    $dataRow->coinMarketCapCoinName = $coinMarketTicker['name'];
                    $dataRow->save();
                }
            }

        }

        return 'success';
    }

}