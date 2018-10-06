<?php

namespace App\Http\Controllers\BittrexExchange;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;


class TradingRulesController extends Controller
{

    public function index()
    {

        return View::make('bittrex/tradingrules/index');
    }

}
