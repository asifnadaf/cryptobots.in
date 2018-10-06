@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Chart of {{$marketName}}</h1>
            </div>
        </div>

        <div class="app">
            <center>
                {!! $btcInUSDTChart->html() !!}
            </center>
        </div>
        <!-- End Of Main Application -->
        {!! Charts::scripts() !!}
        {!! $btcInUSDTChart->script() !!}

        <div class="app">
            <center>
                {!! $altcoinInBTCChart->html() !!}
            </center>
        </div>
        <!-- End Of Main Application -->
        {!! $altcoinInBTCChart->script() !!}


        <div class="app">
            <center>
                {!! $altcoinInUSDTChart->html() !!}
            </center>
        </div>
        <!-- End Of Main Application -->
        {!! Charts::scripts() !!}
        {!! $altcoinInUSDTChart->script() !!}



    </div>
@stop



