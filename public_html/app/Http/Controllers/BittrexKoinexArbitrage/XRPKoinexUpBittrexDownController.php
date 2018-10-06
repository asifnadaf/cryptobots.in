<?php

namespace App\Http\Controllers\BittrexKoinexArbitrage;

use App\Http\Controllers\Controller;
use Log;
use Mail;
use Charts;
use App\Classes\BittrexKoinexArbitrageUtilities\XRPKoinexUpBittrexDownUtilities;
use App\Http\Requests;
use Illuminate\Support\Facades\View;

class XRPKoinexUpBittrexDownController extends Controller
{
    public function index()
    {
        $xrpKoinexUpBittrexDownUtilities = new XRPKoinexUpBittrexDownUtilities();
        $result = $xrpKoinexUpBittrexDownUtilities->findOpportunity();
        return View::make('bittrex/bittrexkoinexarbitrage/arbitrage/XRPKoinexUpBittrexDown/index', compact('result'));
    }
}
