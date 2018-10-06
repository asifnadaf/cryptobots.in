<?php

namespace App\Http\Controllers\BittrexKoinexArbitrage;

use App\Http\Controllers\Controller;
use Log;
use Mail;
use Charts;
use App\Classes\BittrexKoinexArbitrageUtilities\XRPBittrexUpKoinexDownUtilities;
use App\Http\Requests;
use Illuminate\Support\Facades\View;

class XRPBittrexUpKoinexDownController extends Controller
{
    public function index()
    {
        $xrpBittrexUpKoinexDownUtilities = new XRPBittrexUpKoinexDownUtilities();
        $result = $xrpBittrexUpKoinexDownUtilities->findOpportunity();
        return View::make('bittrex/bittrexkoinexarbitrage/arbitrage/XRPBittrexUpKoinexDown/index', compact('result'));
    }
}
