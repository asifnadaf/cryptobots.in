<?php

namespace App\Http\Controllers\BittrexKoinexArbitrage;

use App\Http\Controllers\Controller;
use Log;
use Mail;
use Charts;
use App\Classes\BittrexKoinexArbitrageUtilities\ETHKoinexUpBittrexDownUtilities;
use App\Http\Requests;
use Illuminate\Support\Facades\View;

class ETHKoinexUpBittrexDownController extends Controller
{
    public function index()
    {
        $ethKoinexUpBittrexDownUtilities = new ETHKoinexUpBittrexDownUtilities();
        $result = $ethKoinexUpBittrexDownUtilities->findOpportunity();
        return View::make('bittrex/bittrexkoinexarbitrage/arbitrage/ETHKoinexUpBittrexDown/index', compact('result'));
    }
}
