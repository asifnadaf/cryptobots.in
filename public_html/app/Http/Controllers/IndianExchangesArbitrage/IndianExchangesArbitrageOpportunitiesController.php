<?php

namespace App\Http\Controllers\IndianExchangesArbitrage;

use App\Http\Controllers\Controller;
use App\Models\IndianExchangesArbitrageOpportunitiesTrackerModel;
use Log;
use Mail;
use Charts;

use App\Http\Requests;
use Illuminate\Support\Facades\View;

class IndianExchangesArbitrageOpportunitiesController extends Controller
{
    public function index()
    {
        $data = IndianExchangesArbitrageOpportunitiesTrackerModel::orderBy('updated_at', 'desc')->paginate(100);
        return View::make('bittrex/indianexchangesarbitrage/opportunities/index', compact('data'));
    }

}
