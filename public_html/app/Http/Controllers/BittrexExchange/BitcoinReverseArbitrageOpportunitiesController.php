<?php

namespace App\Http\Controllers\BittrexExchange;

use App\Models\BitcoinReverseArbitrageOpportunitiesModel;
use App\Classes\CurrenciesUtilities;

use Log;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;

class BitcoinReverseArbitrageOpportunitiesController extends Controller
{

    public function index()
    {
        $bitcoinReverseArbitrageOpportunitiesModel = new BitcoinReverseArbitrageOpportunitiesModel();
        $bitcoinArbitrageOpportunitiesData = $bitcoinReverseArbitrageOpportunitiesModel::orderBy('created_at', 'DESC')->get();
        $currenciesUtilities = new CurrenciesUtilities();

        $currentData = $currenciesUtilities->getReverseArbitrageOpportunity();
        if(count($currentData)>0){
            $currentData[] = $currentData;

        }else{
            $currentData = null;
        }
        return View::make('bittrex/bitcoinreversearbitrageopportunities/index', compact('bitcoinArbitrageOpportunitiesData', 'currentData'));
    }

}
