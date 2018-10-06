@extends('layouts.index')

@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Altcoin information</h1>
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
                                    <th>Rank</th>
                                    <th>Coin Name</th>
                                    <th>Symbol</th>
                                    <th class="text-left">Remark</th>
                                    <th class="text-right">Price (BTC)</th>
                                    <th class="text-right">Price (USD)</th>
                                    <th class="text-right">Volume (USD)</th>
                                    <th class="text-right">Market cap (mil. USD)</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($tickers) > 0)
                                    <?php $i = 1; ?>
                                    @forelse ($tickers as $ticker)
                                        <tr>
                                            <td>{{ $ticker['rank'] }}</td>
                                            <td>{{ $ticker['coinMarketCapCoinName'] }}</td>
                                            <td>{{ $ticker['symbol'] }}</td>
                                            <td>{{ $ticker['remark'] }}</td>
                                            <td class="text-right">{{ number_format($ticker['price_btc'], 8) }}</td>
                                            <td class="text-right">{{ number_format($ticker['price_usd'], 2) }}</td>
                                            <td class="text-right">{{ number_format($ticker['24h_volume_usd'], 2) }}</td>
                                            <td class="text-right">{{ $ticker['market_cap_usd'] / 1000000}}</td>
                                            <td><a href="{{URL::to('/')}}/altcoininfo/{{ $ticker['id'] }}/edit">edit</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">No altcoin information found</td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="8">No data feed available. Please try again after sometime</td>
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
