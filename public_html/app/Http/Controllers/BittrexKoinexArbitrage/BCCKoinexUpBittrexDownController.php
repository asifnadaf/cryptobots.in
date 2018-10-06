<?php

namespace App\Http\Controllers\BittrexKoinexArbitrage;

use App\Http\Controllers\Controller;
use Log;
use Mail;
use Charts;
use App\Classes\BittrexKoinexArbitrageUtilities\BCCKoinexUpBittrexDownUtilities;
use App\Http\Requests;
use Illuminate\Support\Facades\View;

class BCCKoinexUpBittrexDownController extends Controller
{
    public function index()
    {
        $bccKoinexUpBittrexDownUtilities = new BCCKoinexUpBittrexDownUtilities();
        $result = $bccKoinexUpBittrexDownUtilities->findOpportunity();
        return View::make('bittrex/bittrexkoinexarbitrage/arbitrage/BCCKoinexUpBittrexDown/index', compact('result'));
    }
}
