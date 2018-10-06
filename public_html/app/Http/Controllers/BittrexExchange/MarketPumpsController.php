<?php

namespace App\Http\Controllers\BittrexExchange;

use App\Models\MarketPumpsModel;
use Log;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;

class MarketPumpsController extends Controller
{

    public function index()
    {
        $marketPumpsModel = new MarketPumpsModel();
        $marketPumpsData = $marketPumpsModel::orderBy('TimeStamp','DESC')->get();
        return View::make('bittrex/marketpumps/index',compact('marketPumpsData'));
    }

}
