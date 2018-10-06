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
                <h1 class="page-header text-capitalize">Order History</h1>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <h4 class="text-capitalize">Altcoins
                    Balances: {{number_format($accountBalance['totalBalanceIn_BTC'], 8 )}} BTC / {{number_format($accountBalance['totalBalanceIn_USDT'], 8 )}} USDT</h4>
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
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($orderHistory) > 0)
                                    <?php $i = 1; ?>
                                    @forelse ($orderHistory as $row)
                                        <tr>
                                            <td class="text-left">{{ $i++ }}</td>
                                            <td class="text-left">{{ Carbon\Carbon::parse($row->Closed,'UCT')->setTimezone('Asia/Kolkata')->format('d-m-Y h:i:s A') }}</td>
                                            <td class="text-left">{{ Carbon\Carbon::parse($row->TimeStamp,'UCT')->setTimezone('Asia/Kolkata')->format('d-m-Y h:i:s A') }}</td>
                                            <td class="text-left"><a
                                                        href="{{URL::to('https://bittrex.com/Market/Index?MarketName=')}}{{ $row->Exchange }}"
                                                        target="_blank">{{ $row->Exchange }}</a></td>
                                            <td class="text-left text-capitalize">{{ str_replace('_', ' ', $row->OrderType) }}</td>
                                            <td class="text-right">{{ number_format($row->Limit, 8) }}</td>
                                            <td class="text-right">{{ number_format($row->Quantity - $row->QuantityRemaining, 8 ) }}</td>
                                            <td class="text-right"><a
                                                        href="{{URL::to('/')}}/clients/exchange/{{ $clientData->id }}/altcoin/order/history/{{ $row->Exchange }}"
                                                        target="_blank">{{ number_format($row->Quantity, 8 ) }} </a>
                                            </td>
                                            <td class="text-right">{{ number_format($row->PricePerUnit, 8 )}} </td>
                                            @if($row->OrderType == 'LIMIT_BUY')
                                                <td class="text-right">{{ number_format($row->Price + $row->Commission, 8) }} </td>
                                            @endif
                                            @if($row->OrderType == 'LIMIT_SELL')
                                                <td class="text-right">{{ number_format($row->Price - $row->Commission, 8) }} </td>
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


    </div>
@stop
