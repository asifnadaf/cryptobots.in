<?php

namespace App\Http\Controllers\BittrexKoinexArbitrage;

use App\Http\Controllers\Controller;
use Log;
use Mail;
use Charts;
use App\Classes\BittrexKoinexArbitrageUtilities\BCCBittrexUpKoinexDownUtilities;
use App\Http\Requests;
use Illuminate\Support\Facades\View;

class BCCBittrexUpKoinexDownController extends Controller
{
    public function index()
    {
        $bccBittrexUpKoinexDownUtilities = new BCCBittrexUpKoinexDownUtilities();
        $result = $bccBittrexUpKoinexDownUtilities->findOpportunity();
        return View::make('bittrex/bittrexkoinexarbitrage/arbitrage/BCCBittrexUpKoinexDown/index', compact('result'));
    }
}
