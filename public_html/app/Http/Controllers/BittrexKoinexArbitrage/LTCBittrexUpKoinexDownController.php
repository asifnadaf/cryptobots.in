<?php

namespace App\Http\Controllers\BittrexKoinexArbitrage;

use App\Http\Controllers\Controller;
use Log;
use Mail;
use Charts;
use App\Classes\BittrexKoinexArbitrageUtilities\LTCBittrexUpKoinexDownUtilities;
use App\Http\Requests;
use Illuminate\Support\Facades\View;

class LTCBittrexUpKoinexDownController extends Controller
{
    public function index()
    {
        $ltcBittrexUpKoinexDownUtilities = new LTCBittrexUpKoinexDownUtilities();
        $result = $ltcBittrexUpKoinexDownUtilities->findOpportunity();
        return View::make('bittrex/bittrexkoinexarbitrage/arbitrage/LTCBittrexUpKoinexDown/index', compact('result'));
    }
}
