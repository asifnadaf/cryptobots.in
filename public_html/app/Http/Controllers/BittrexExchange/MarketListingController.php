<?php

namespace App\Http\Controllers\BittrexExchange;

use App\Models\MarketListingModel;
use Log;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;

class MarketListingController extends Controller
{

    public function index()
    {
        $marketListingModel = new MarketListingModel();
        $marketListingData = $marketListingModel::orderBy('created_at','DESC')->get();
        return View::make('bittrex/marketlisting/index',compact('marketListingData'));
    }

}
