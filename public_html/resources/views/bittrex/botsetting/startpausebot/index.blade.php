@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Start / Pause Bots Settings</h1>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="{{URL::to('/')}}/startpausebotsetting/{{ $settings->id }}/edit" class="btn btn-primary">Edit</a>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <tr>
                                    <td>Pause buy at support bot</td>
                                    <td>{{ $settings->pauseBuyAtSupportBot }}</td>
                                </tr>
                                <tr>
                                    <td>Pause sell on resistance price bot</td>
                                    <td>{{ $settings->pauseSellOnResistancePriceBot }}</td>
                                </tr>
                                <tr>
                                    <td>Pause update sell limit orderbook to X times bot</td>
                                    <td>{{ $settings->pauseUpdateSellLimitOrderBookToXTimesBot }}</td>
                                </tr>
                            </table>
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>
@stop
