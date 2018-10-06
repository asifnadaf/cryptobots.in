<?php

namespace App\Http\Controllers\BittrexExchange;

use App\Models\MarketDumpsModel;
use Log;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;

class MarketDumpsController extends Controller
{
    public function index()
    {
        $marketDumpsModel = new MarketDumpsModel();
        $marketDumpsData = $marketDumpsModel::orderBy('TimeStamp','DESC')->get();
        return View::make('bittrex/marketdumps/index',compact('marketDumpsData'));
    }

}
