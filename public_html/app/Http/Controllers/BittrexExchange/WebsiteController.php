<?php

namespace App\Http\Controllers\BittrexExchange;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;



class WebsiteController extends Controller
{

    public function landingPage() {
        return View::make('bittrex/website/index');
    }

}
