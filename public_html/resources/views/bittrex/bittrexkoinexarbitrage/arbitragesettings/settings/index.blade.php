@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Arbitrage Settings</h1>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="{{URL::to('/')}}/kbas/{{ $settings->id }}/edit" class="btn btn-primary">Edit</a>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <tr>
                                    <td>Minimum trade size</td>
                                    <td>{{ $settings->minimumTradeSize }}</td>
                                </tr>
                                <tr>
                                    <td>Maximum trade size</td>
                                    <td>{{ $settings->maximumTradeSize }}</td>
                                </tr>
                                <tr>
                                    <td>Minimum gross percent gain</td>
                                    <td>{{ $settings->minimumGrossPercentGain }}</td>
                                </tr>

                                <tr>
                                    <td>Bittrex bid above lowest ask by percent</td>
                                    <td>{{ $settings->bittrexBidAboveLowestAskByPercent }}</td>
                                </tr>

                                <tr>
                                    <td>Check Koinex ticker volume updated timestamp?</td>
                                    <td>{{ $settings->lookAtKoinexTickerVolumeUpdateTimestamp }}</td>
                                </tr>

                                <tr>
                                    <td>Bittrex ask below highest bid by percent</td>
                                    <td>{{ $settings->bittrexAskBelowHighestBidByPercent }}</td>
                                </tr>

                                <tr>
                                    <td>Koinex bid above lowest ask by percent</td>
                                    <td>{{ $settings->koinexBidAboveLowestAskByPercent }}</td>
                                </tr>

                                <tr>
                                    <td>Koinex ask below highest bid by percent</td>
                                    <td>{{ $settings->koinexAskBelowHighestBidByPercent }}</td>
                                </tr>
                            </table>
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>
@stop
