<?php

namespace App\Http\Controllers\BittrexKoinexArbitrage;

use App\Http\Controllers\Controller;
use Log;
use Mail;
use Charts;
use App\Classes\BittrexKoinexArbitrageUtilities\ETHBittrexUpKoinexDownUtilities;
use App\Http\Requests;
use Illuminate\Support\Facades\View;

class ETHBittrexUpKoinexDownController extends Controller
{
    public function index()
    {
        $ethBittrexUpKoinexDownUtilities = new ETHBittrexUpKoinexDownUtilities();
        $result = $ethBittrexUpKoinexDownUtilities->findOpportunity();
        return View::make('bittrex/bittrexkoinexarbitrage/arbitrage/ETHBittrexUpKoinexDown/index', compact('result'));
    }
}
