<?php

namespace App\Http\Controllers\BittrexExchange;

use App\Models\MarketDelistingModel;
use Log;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;

class MarketDelistingController extends Controller
{
    public function index()
    {
        $marketDelistingModel = new MarketDelistingModel();
        $marketDelistingData = $marketDelistingModel::orderBy('created_at','DESC')->get();
        return View::make('bittrex/marketdelisting/index',compact('marketDelistingData'));
    }
}
