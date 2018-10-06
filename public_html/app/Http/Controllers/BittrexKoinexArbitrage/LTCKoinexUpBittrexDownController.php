<?php

namespace App\Http\Controllers\BittrexKoinexArbitrage;

use App\Http\Controllers\Controller;
use Log;
use Mail;
use Charts;
use App\Classes\BittrexKoinexArbitrageUtilities\LTCKoinexUpBittrexDownUtilities;
use App\Http\Requests;
use Illuminate\Support\Facades\View;

class LTCKoinexUpBittrexDownController extends Controller
{
    public function index()
    {
        $ltcKoinexUpBittrexDownUtilities = new LTCKoinexUpBittrexDownUtilities();
        $result = $ltcKoinexUpBittrexDownUtilities->findOpportunity();
        return View::make('bittrex/bittrexkoinexarbitrage/arbitrage/LTCKoinexUpBittrexDown/index', compact('result'));
    }
}
