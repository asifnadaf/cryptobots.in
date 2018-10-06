@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">XRP - Koinex Up Bittrex Down&nbsp;&nbsp;&nbsp;&nbsp;
                    @if(!empty($result['instructions']))
                        <img alt="limited time offer"
                             src="{{ URL::to('images/green_box.png') }}"/>
                    @else
                        <img alt="limited time offer"
                             src="{{ URL::to('images/red_box.png') }}"/>
                    @endif

                </h1>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>Current prices</b>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>Delay (in seconds)</th>
                                    <th>Returns</th>

                                    <th>BTC-XRP rate</th>
                                    <th>BTC-XRP quantity</th>

                                    <th>INR-XRP rate</th>
                                    <th>INR-XRP quantity</th>

                                    <th>INR-BTC rate</th>
                                    <th>INR-BTC quantity</th>
                                    <th>Xsaction Value (INR)</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($result['data']))
                                    <tr>
                                        <td>{{ $result['data']['timestamp'] }}</td>
                                        <td>{{ $result['data']['returns'] }}</td>

                                        <td>{{ number_format($result['data']['BTC-XRP']['Rate'] , 8) }}</td>
                                        <td>{{ number_format($result['data']['BTC-XRP']['Quantity'] , 8) }}</td>

                                        <td>{{ number_format($result['data']['INR-XRP']['Rate'] , 8) }}</td>
                                        <td>{{ number_format($result['data']['INR-XRP']['Quantity'] , 8) }}</td>

                                        <td>{{ number_format($result['data']['INR-BTC']['Rate'] , 8) }}</td>
                                        <td>{{ number_format($result['data']['INR-BTC']['Quantity'] , 8) }}</td>
                                        <td>{{ number_format($result['data']['INR-BTC']['Rate'] * $result['data']['INR-BTC']['Quantity'] , 8) }}</td>

                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        @if(!empty($result['instructions']))
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <b>Trading instructions</b>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <tbody>
                                    @if(!empty($result))
                                        <tr>
                                            <td>1. {{ $result['instructions']['XRP_KoinexUp_BittrexDown']['firstTransaction'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>2. {{ $result['instructions']['XRP_KoinexUp_BittrexDown']['secondTransaction'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>3. {{ $result['instructions']['XRP_KoinexUp_BittrexDown']['thirdTransaction'] }}</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(!empty($result['message']))
            <div class="col-lg-12">
                <div class="alert alert-danger" role="alert">
                    {{$result['message']}}
                </div>
            </div>
        @endif

    </div>
@stop
