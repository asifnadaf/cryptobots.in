<?php

namespace App\Http\Controllers\BittrexKoinexArbitrage;

use App\Http\Controllers\Controller;
use App\Models\KoinexBittrexArbitrageOpportunitiesTrackerModel;
use App\Models\BittrexKoinexArbitrageSettingsModel;
use Log;
use Mail;
use Charts;

use App\Http\Requests;
use Illuminate\Support\Facades\View;

class ArbitrageOpportunitiesController extends Controller
{
    public function index()
    {
        $data = KoinexBittrexArbitrageOpportunitiesTrackerModel::orderBy('updated_at', 'desc')->paginate(100);
        return View::make('bittrex/bittrexkoinexarbitrage/opportunities/index', compact('data'));
    }

    public function removeNegativeReturns()
    {

        $settings = BittrexKoinexArbitrageSettingsModel::first();

        $minimumGrossPercentGain = $settings->minimumGrossPercentGain;

        $allData = KoinexBittrexArbitrageOpportunitiesTrackerModel::all();
        foreach ($allData as $row) {
            if ($row->grossPercentGain < $minimumGrossPercentGain) {
                KoinexBittrexArbitrageOpportunitiesTrackerModel::where('id', $row->id)->delete();
            }
        }
        return redirect('/bittrex/koinex/arbitrage/opportunities');
    }

}
