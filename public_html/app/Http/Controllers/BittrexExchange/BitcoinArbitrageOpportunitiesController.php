<?php

namespace App\Http\Controllers\BittrexExchange;

use App\Models\BitcoinArbitrageOpportunitiesModel;
use App\Classes\CurrenciesUtilities;

use Log;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;

class BitcoinArbitrageOpportunitiesController extends Controller
{

    public function index()
    {
        $bitcoinArbitrageOpportunitiesModel = new BitcoinArbitrageOpportunitiesModel();
        $bitcoinArbitrageOpportunitiesData = $bitcoinArbitrageOpportunitiesModel::orderBy('created_at','DESC')->get();

        $currenciesUtilities = new CurrenciesUtilities();
        $currentData = $currenciesUtilities->getArbitrageOpportunity();

        if(count($currentData)>0){
            $currentData[] = $currentData;

        }else{
            $currentData = null;
        }
        return View::make('bittrex/bitcoinarbitrageopportunities/index',compact('bitcoinArbitrageOpportunitiesData','currentData'));
    }

}
