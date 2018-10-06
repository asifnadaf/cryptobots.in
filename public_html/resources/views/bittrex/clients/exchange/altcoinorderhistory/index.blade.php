@extends('layouts.index')

@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize text-center">Client name: {{ $clientData->fullName }}</h1>
            </div>
        </div>
        <div class="row">
            @if($accountError!=null)
                <div class="alert alert-danger" role="alert">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span>
                    {{$accountError}}
                </div>
            @endif

        </div>


        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">{{$marketName}}</h1>
            </div>

            <div class="col-lg-12">
                <h4>Bid: {{ number_format($ticker->Bid, 8 )}} &nbsp;&nbsp;&nbsp;&nbsp;
                    Last: {{ number_format($ticker->Last, 8 )}} &nbsp;&nbsp;&nbsp;&nbsp;
                    Ask: {{ number_format($ticker->Ask, 8 )}}
                </h4>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">Open Orders</h1>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th class="text-left">#</th>
                                    <th class="text-left">Order date</th>
                                    <th class="text-left">Market</th>
                                    <th class="text-left">Type</th>
                                    <th class="text-left">Bid/Ask</th>
                                    <th class="text-right">Units filled</th>
                                    <th class="text-right">Units total</th>
                                    <th class="text-right">Actual rate</th>
                                    <th class="text-right">Estimated total</th>
                                    <th class="text-right">% change after purchase (BTC)</th>
                                    <th class="text-right">Last 24 hours % change (BTC)</th>
                                    <th class="text-right">Invested amount (BTC)</th>
                                    <th class="text-right">Current value (BTC)</th>
                                    <th class="text-right">BTC to USDT purchase rate</th>
                                    <th class="text-right">BTC to USDT current rate</th>
                                    <th class="text-right">% Change BTC to USDT</th>
                                    <th class="text-right">% change after purchase (USDT)</th>
                                    <th class="text-right">Last 24 hours % change (USDT)</th>
                                    <th class="text-right">Invested amount (USDT)</th>
                                    <th class="text-right">Current value (USDT)</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($openOrders) > 0)
                                    <?php $i = 1; ?>
                                    @forelse ($openOrders as $openOrder)
                                        <tr>
                                            <td class="text-left">{{ $i++ }}</td>
                                            <td class="text-left">{{ Carbon\Carbon::parse($openOrder->Opened,'UCT')->setTimezone('Asia/Kolkata')->format('d-m-Y h:i:s A') }}</td>
                                            <td class="text-left"><a
                                                        href="{{URL::to('https://bittrex.com/Market/Index?MarketName=')}}{{ $openOrder->Exchange }}"
                                                        target="_blank">{{ $openOrder->Exchange }}</a>
                                            </td>
                                            <td class="text-left text-capitalize">{{ str_replace('_', ' ', $openOrder->OrderType) }}</td>
                                            <td class="text-right"><a
                                                        href="{{URL::to('/')}}/odds/altcoin/chart/{{ $openOrder->Exchange }}">{{ number_format($openOrder->Limit, 8) }}</a>
                                            </td>
                                            <td class="text-right">{{ number_format($openOrder->Quantity - $openOrder->QuantityRemaining, 8 ) }}  </td>
                                            <td class="text-right"><a
                                                        href="{{URL::to('/')}}/clients/altcoinorderhistory/{{ $clientData->id }}/{{ $openOrder->Exchange }}"
                                                        target="_blank">{{ number_format($openOrder->Quantity, 8 ) }} </a>
                                            </td>
                                            <td class="text-right">{{ number_format($openOrder->Price, 8 ) }} </td>
                                            @if($openOrder->OrderType == 'LIMIT_BUY')
                                                <td class="text-right">{{ number_format($openOrder->QuantityRemaining * $openOrder->Limit * 1.0025, 8) }}</td>
                                                <td class="text-right">{{ $openOrder->percentChangeInBTC}}</td>
                                                <td class="text-right">{{ $openOrder->last24HoursPercentChangeInBTC}}</td>
                                                <td class="text-right">{{ $openOrder->investedAmountInBTC}}</td>
                                                <td class="text-right">{{ $openOrder->currentValueInBTC}}</td>
                                                <td class="text-right">{{ $openOrder->BTCRateInUSDTAtTheTimeOfPurchase}}</td>
                                                <td class="text-right">{{ $openOrder->currentBTCRateInUSDT}}</td>
                                                <td class="text-right">{{ $openOrder->percentChangeUSDTToBTC}}</td>
                                                <td class="text-right">{{ $openOrder->percentChangeInUSDT }}</td>
                                                <td class="text-right">{{ $openOrder->last24HoursPercentChangeInUSDT}}</td>
                                                <td class="text-right">{{ $openOrder->investedAmountInUSDT}}</td>
                                                <td class="text-right">{{ $openOrder->currentValueInUSDT}}</td>
                                            @endif
                                            @if($openOrder->OrderType == 'LIMIT_SELL')
                                                <td class="text-right">{{ number_format($openOrder->QuantityRemaining * $openOrder->Limit * 0.9975, 8) }}</td>
                                                <td class="text-right">{{ number_format($openOrder->percentChangeInBTC, 2) }}</td>
                                                <td class="text-right">{{ number_format($openOrder->last24HoursPercentChangeInBTC, 2) }}</td>
                                                <td class="text-right">{{ number_format($openOrder->investedAmountInBTC, 8) }}</td>
                                                <td class="text-right">{{ number_format($openOrder->currentValueInBTC, 8) }}</td>
                                                <td class="text-right">{{ number_format($openOrder->BTCRateInUSDTAtTheTimeOfPurchase, 2) }}</td>
                                                <td class="text-right">{{ number_format($openOrder->currentBTCRateInUSDT, 2) }}</td>
                                                <td class="text-right">{{ number_format($openOrder->percentChangeInUSDT, 2) }}</td>
                                                <td class="text-right">{{ number_format($openOrder->percentChangeInUSDT, 2) }}</td>
                                                <td class="text-right">{{ number_format($openOrder->last24HoursPercentChangeInUSDT, 2) }}</td>
                                                <td class="text-right">{{ number_format($openOrder->investedAmountInUSDT, 8) }}</td>
                                                <td class="text-right">{{ number_format($openOrder->currentValueInUSDT, 8) }}</td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">You have no open orders.</td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="10">No open orders found. Please try again after sometime.</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">Order History</h1>
            </div>

        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th class="text-left">#</th>
                                    <th class="text-left">Closed date</th>
                                    <th class="text-left">Opened date</th>
                                    <th class="text-left">Market</th>
                                    <th class="text-left">Type</th>
                                    <th class="text-right">Bid/Ask</th>
                                    <th class="text-right">Units filled</th>
                                    <th class="text-right">Units total</th>
                                    <th class="text-right">Actual rate</th>
                                    <th class="text-right">Cost proceeds</th>
                                    <th class="text-right">Total (in BTC)</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($orderHistory) > 0)
                                    <?php $i = 1; ?>
                                    @forelse ($orderHistory as $ch)
                                        <tr>
                                            <td class="text-left">{{ $i++ }}</td>
                                            <td class="text-left">{{ Carbon\Carbon::parse($ch->Closed,'UCT')->setTimezone('Asia/Kolkata')->format('d-m-Y h:i:s A') }}</td>
                                            <td class="text-left">{{ Carbon\Carbon::parse($ch->TimeStamp,'UCT')->setTimezone('Asia/Kolkata')->format('d-m-Y h:i:s A') }}</td>
                                            <td class="text-left"><a
                                                        href="{{URL::to('https://bittrex.com/Market/Index?MarketName=')}}{{ $ch->Exchange }}"
                                                        target="_blank">{{ $ch->Exchange }}</a>
                                            </td>
                                            <td class="text-left text-capitalize">{{ str_replace('_', ' ', $ch->OrderType) }}</td>
                                            <td class="text-right">{{ number_format($ch->Limit, 8) }}</td>
                                            <td class="text-right">{{ number_format($ch->Quantity - $ch->QuantityRemaining, 8 ) }}  </td>
                                            <td class="text-right">{{ number_format($ch->Quantity, 8 ) }} </td>
                                            <td class="text-right">{{ number_format($ch->PricePerUnit, 8 )}} </td>
                                            @if($ch->OrderType == 'LIMIT_BUY')
                                                <td class="text-right">{{ number_format($ch->Price + $ch->Commission, 8) }} </td>
                                                <td class="text-right">{{ number_format($ch->altcoinToBTC, 8 ) }} </td>
                                            @endif
                                            @if($ch->OrderType == 'LIMIT_SELL')
                                                <td class="text-right">{{ number_format($ch->Price - $ch->Commission, 8) }} </td>
                                                <td class="text-right">{{ number_format($ch->altcoinToBTC, 8 ) }} </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">You have no open orders.</td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="10">No open orders found. Please try again after sometime.</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>

            </div>
        </div>


    </div>
@stop
