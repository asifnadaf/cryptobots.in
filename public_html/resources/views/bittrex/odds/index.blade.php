@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Market Odds Prices</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        BTC prices
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Exchange name</th>
                                    <th>Market name</th>
                                    <th>is Buying paused?</th>
                                    <th>Is selling on equal odds paused?</th>
                                    <th>Is selling at 2X paused?</th>
                                    <th>Is selling on resistance paused?</th>
                                    <th class="text-right">Support & last % diff</th>
                                    <th class="text-right">Base volume</th>
                                    <th class="text-right">Support & equal odds ratio</th>
                                    <th class="text-right">Lowest price (Last)</th>
                                    <th class="text-right">Average price</th>
                                    <th class="text-right">Average return</th>
                                    <th class="text-right">Expected gain</th>
                                    <th class="text-right">Expected return</th>
                                    <th class="text-right">Total row count</th>
                                    <th class="text-right">Row count of support</th>
                                    <th class="text-right">Support price</th>
                                    <th class="text-right">Row count of equal odds</th>
                                    <th class="text-right">Equal odds price</th>
                                    <th class="text-right">Row count resistance</th>
                                    <th class="text-right">Resistance price</th>
                                    <th class="text-right">Resistance & equal odds ratio</th>
                                    <th class="text-right">Last price</th>
                                    <th class="text-right">Resistance & support % diff</th>
                                    <th class="text-right">Updated date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($USDTBTCPrices) > 0)
                                    <?php $i = 1; ?>
                                    @forelse ($USDTBTCPrices as $USDTBTCPrice)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $USDTBTCPrice->exchangeName }}</td>
                                            <td>
                                                <a href="{{URL::to('https://bittrex.com/Market/Index?MarketName=')}}{{ $USDTBTCPrice->MarketName }}"
                                                   target="_blank">{{ $USDTBTCPrice->MarketName }}</a>
                                            </td>
                                            <td>{{ $USDTBTCPrice->isBuyingPaused }}</td>
                                            <td>{{ $USDTBTCPrice->isSellingOnEqualOddsPaused }}</td>
                                            <td>{{ $USDTBTCPrice->isSellingAt2XPaused }}</td>
                                            <td>{{ $USDTBTCPrice->isSellingOnResistancePaused }}</td>

                                            <td class="text-right">{{ number_format($USDTBTCPrice->supportNLastPercentageDifference, 2) }}
                                                %
                                            </td>
                                            <td class="text-right">{{ number_format($USDTBTCPrice->BaseVolume, 2) }}</td>
                                            <td class="text-right">{{ number_format($USDTBTCPrice->supportAndEqualOddsRatio, 2) }}</td>
                                            <td class="text-right">{{ number_format($USDTBTCPrice->lowestPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($USDTBTCPrice->averagePrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($USDTBTCPrice->averageReturn, 2) }}
                                                %
                                            </td>
                                            <td class="text-right">{{ number_format($USDTBTCPrice->expectedPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($USDTBTCPrice->expectedReturn, 2) }}
                                                %
                                            </td>
                                            <td class="text-right">{{ number_format($USDTBTCPrice->numberOfRows, 0) }}</td>
                                            <td class="text-right">{{ number_format($USDTBTCPrice->needToCountForSupportPrice, 0) }}</td>
                                            <td class="text-right">{{ number_format($USDTBTCPrice->supportPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($USDTBTCPrice->needToCountForEqualOddsPrice, 0) }}</td>
                                            <td class="text-right">{{ number_format($USDTBTCPrice->equalOddsPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($USDTBTCPrice->needToCountForResistancePrice, 0) }}</td>
                                            <td class="text-right">{{ number_format($USDTBTCPrice->resistancePrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($USDTBTCPrice->supportAndResistanceOddsRatio, 2) }}</td>
                                            <td class="text-right">{{ number_format($USDTBTCPrice->Last, 8) }}</td>
                                            <td class="text-right">{{ number_format($USDTBTCPrice->resistanceNSupportPercentageDifference, 2) }}
                                                %
                                            </td>
                                            <td class="text-right">{{ $USDTBTCPrice->updated_at }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">No market odds found</td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="10">No market odds. Please try again after sometime.</td>
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
                <p><strong>Conditions</strong></p>
                <p><span class="glyphicon glyphicon-hand-right">&nbsp;</span>Last price <= Support price</p>
                <p><span class="glyphicon glyphicon-hand-right">&nbsp;</span>Minimum
                    volume: {{$minimumVolumeOfBaseCurrencyBTC}}</p>
                <p><span class="glyphicon glyphicon-hand-right">&nbsp;</span>Support and equal odds
                    ratio: {{$supportAndEqualOddsRatio}}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Near support prices
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Exchange name</th>
                                    <th>Market name</th>
                                    <th>is Buying paused?</th>
                                    <th>Is selling on equal odds paused?</th>
                                    <th>Is selling at 2X paused?</th>
                                    <th>Is selling on resistance paused?</th>
                                    <th class="text-right">Support & last % diff</th>
                                    <th class="text-right">Pump counts</th>
                                    <th class="text-right">Base volume</th>
                                    <th class="text-right">Average base volume</th>
                                    <th class="text-right">Support & equal odds ratio</th>
                                    <th class="text-right">Lowest price (Last)</th>
                                    <th class="text-right">Average price</th>
                                    <th class="text-right">Average return</th>
                                    <th class="text-right">Expected gain</th>
                                    <th class="text-right">Expected return</th>
                                    <th class="text-right">Total row count</th>
                                    <th class="text-right">Row count of support</th>
                                    <th class="text-right">Support price</th>
                                    <th class="text-right">Row count of equal odds</th>
                                    <th class="text-right">Equal odds price</th>
                                    <th class="text-right">Row count resistance</th>
                                    <th class="text-right">Resistance price</th>
                                    <th class="text-right">Resistance & equal odds ratio</th>
                                    <th class="text-right">Last price</th>
                                    <th class="text-right">Resistance & support % diff</th>
                                    <th class="text-right">Updated date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($supportPricesCurrencies) > 0)
                                    <?php $i = 1; ?>
                                    @forelse ($supportPricesCurrencies as $supportPricesCurrency)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $supportPricesCurrency->exchangeName }}</td>
                                            <td>
                                                <a href="{{URL::to('https://bittrex.com/Market/Index?MarketName=')}}{{ $supportPricesCurrency->MarketName }}"
                                                   target="_blank">{{ $supportPricesCurrency->MarketName }}</a>
                                            </td>
                                            <td>{{ $supportPricesCurrency->isBuyingPaused }}</td>
                                            <td>{{ $supportPricesCurrency->isSellingOnEqualOddsPaused }}</td>
                                            <td>{{ $supportPricesCurrency->isSellingAt2XPaused }}</td>
                                            <td>{{ $supportPricesCurrency->isSellingOnResistancePaused }}</td>
                                            <td class="text-right"><a
                                                        href="{{URL::to('/')}}/odds/altcoin/chart/{{ $supportPricesCurrency->MarketName }}" target="_blank">{{ number_format($supportPricesCurrency->supportNLastPercentageDifference, 2) }}%</a>
                                            </td>
                                            <td class="text-right">{{ $supportPricesCurrency->pumpCounts }}</td>
                                            <td class="text-right">{{ $supportPricesCurrency->BaseVolume }}</td>
                                            <td class="text-right">{{ $supportPricesCurrency->averageBaseVolume }}</td>
                                            <td class="text-right">{{ number_format($supportPricesCurrency->supportAndEqualOddsRatio, 2) }}</td>
                                            <td class="text-right">{{ number_format($supportPricesCurrency->lowestPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($supportPricesCurrency->averagePrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($supportPricesCurrency->averageReturn, 2) }}
                                                %
                                            </td>
                                            <td class="text-right">{{ number_format($supportPricesCurrency->expectedPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($supportPricesCurrency->expectedReturn, 2) }}
                                                %
                                            </td>
                                            <td class="text-right">{{ number_format($supportPricesCurrency->numberOfRows, 0) }}</td>
                                            <td class="text-right">{{ number_format($supportPricesCurrency->needToCountForSupportPrice, 0) }}</td>
                                            <td class="text-right">{{ number_format($supportPricesCurrency->supportPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($supportPricesCurrency->needToCountForEqualOddsPrice, 0) }}</td>
                                            <td class="text-right">{{ number_format($supportPricesCurrency->equalOddsPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($supportPricesCurrency->needToCountForResistancePrice, 0) }}</td>
                                            <td class="text-right">{{ number_format($supportPricesCurrency->resistancePrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($supportPricesCurrency->supportAndResistanceOddsRatio, 2) }}</td>
                                            <td class="text-right">{{ number_format($supportPricesCurrency->Last, 8) }}</td>
                                            <td class="text-right">{{ number_format($supportPricesCurrency->resistanceNSupportPercentageDifference, 2) }}
                                                %
                                            </td>
                                            <td class="text-right">{{ $supportPricesCurrency->updated_at }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">No market odds found</td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="10">No market odds. Please try again after sometime.</td>
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
                <p><strong>Conditions</strong></p>
                <p><span class="glyphicon glyphicon-hand-right">&nbsp;</span>Last price > Support price and Last price <
                    Equal odds price</p>
                <p><span class="glyphicon glyphicon-hand-right">&nbsp;</span>Minimum
                    volume: {{$minimumVolumeOfBaseCurrencyBTC}}</p>
                <p><span class="glyphicon glyphicon-hand-right">&nbsp;</span>Support and equal odds
                    ratio: {{$supportAndEqualOddsRatio}}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Between support and Equal odds price
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Exchange name</th>
                                    <th>Market name</th>
                                    <th>is Buying paused?</th>
                                    <th>Is selling on equal odds paused?</th>
                                    <th>Is selling at 2X paused?</th>
                                    <th>Is selling on resistance paused?</th>
                                    <th class="text-right">Support & last % diff</th>
                                    <th class="text-right">Pump counts</th>
                                    <th class="text-right">Base volume</th>
                                    <th class="text-right">Average base volume</th>
                                    <th class="text-right">Support & equal odds ratio</th>
                                    <th class="text-right">Lowest price (Last)</th>
                                    <th class="text-right">Average price</th>
                                    <th class="text-right">Average return</th>
                                    <th class="text-right">Expected gain</th>
                                    <th class="text-right">Expected return</th>
                                    <th class="text-right">Total row count</th>
                                    <th class="text-right">Row count of support</th>
                                    <th class="text-right">Support price</th>
                                    <th class="text-right">Row count of equal odds</th>
                                    <th class="text-right">Equal odds price</th>
                                    <th class="text-right">Row count resistance</th>
                                    <th class="text-right">Resistance price</th>
                                    <th class="text-right">Resistance & equal odds ratio</th>
                                    <th class="text-right">Last price</th>
                                    <th class="text-right">Resistance & support % diff</th>
                                    <th class="text-right">Updated date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($betweenPricesCurrencies) > 0)
                                    <?php $i = 1; ?>
                                    @forelse ($betweenPricesCurrencies as $betweenPricesCurrency)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $betweenPricesCurrency->exchangeName }}</td>
                                            <td>
                                                <a href="{{URL::to('https://bittrex.com/Market/Index?MarketName=')}}{{ $betweenPricesCurrency->MarketName }}"
                                                   target="_blank">{{ $betweenPricesCurrency->MarketName }}</a>
                                            </td>
                                            <td>{{ $betweenPricesCurrency->isBuyingPaused }}</td>
                                            <td>{{ $betweenPricesCurrency->isSellingOnEqualOddsPaused }}</td>
                                            <td>{{ $betweenPricesCurrency->isSellingAt2XPaused }}</td>
                                            <td>{{ $betweenPricesCurrency->isSellingOnResistancePaused }}</td>
                                            <td class="text-right"><a
                                                        href="{{URL::to('/')}}/odds/altcoin/chart/{{ $betweenPricesCurrency->MarketName }}" target="_blank">{{ number_format($betweenPricesCurrency->supportNLastPercentageDifference, 2) }}%</a>
                                            </td>
                                            <td class="text-right">{{ $betweenPricesCurrency->pumpCounts }}</td>
                                            <td class="text-right">{{ number_format($betweenPricesCurrency->BaseVolume, 2) }}</td>
                                            <td class="text-right">{{ number_format($betweenPricesCurrency->averageBaseVolume, 2) }}</td>
                                            <td class="text-right">{{ number_format($betweenPricesCurrency->supportAndEqualOddsRatio, 2) }}</td>
                                            <td class="text-right">{{ number_format($betweenPricesCurrency->lowestPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($betweenPricesCurrency->averagePrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($betweenPricesCurrency->averageReturn, 2) }}
                                                %
                                            </td>
                                            <td class="text-right">{{ number_format($betweenPricesCurrency->expectedPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($betweenPricesCurrency->expectedReturn, 2) }}
                                                %
                                            </td>
                                            <td class="text-right">{{ number_format($betweenPricesCurrency->numberOfRows, 0) }}</td>
                                            <td class="text-right">{{ number_format($betweenPricesCurrency->needToCountForSupportPrice, 0) }}</td>
                                            <td class="text-right">{{ number_format($betweenPricesCurrency->supportPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($betweenPricesCurrency->needToCountForEqualOddsPrice, 0) }}</td>
                                            <td class="text-right">{{ number_format($betweenPricesCurrency->equalOddsPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($betweenPricesCurrency->needToCountForResistancePrice, 0) }}</td>
                                            <td class="text-right">{{ number_format($betweenPricesCurrency->resistancePrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($betweenPricesCurrency->supportAndResistanceOddsRatio, 2) }}</td>
                                            <td class="text-right">{{ number_format($betweenPricesCurrency->Last, 8) }}</td>
                                            <td class="text-right">{{ number_format($betweenPricesCurrency->resistanceNSupportPercentageDifference, 2) }}
                                                %
                                            </td>
                                            <td class="text-right">{{ $betweenPricesCurrency->updated_at }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">No market odds found</td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="10">No market odds. Please try again after sometime.</td>
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
                <p><strong>Conditions</strong></p>
                <p><span class="glyphicon glyphicon-hand-right">&nbsp;</span>Last price > Equal odds price</p>
                <p><span class="glyphicon glyphicon-hand-right">&nbsp;</span>Minimum
                    volume: {{$minimumVolumeOfBaseCurrencyBTC}}</p>
                <p><span class="glyphicon glyphicon-hand-right">&nbsp;</span>Support and equal odds
                    ratio: {{$supportAndEqualOddsRatio}}</p>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Above Equal odds price
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Exchange name</th>
                                    <th>Market name</th>
                                    <th>is Buying paused?</th>
                                    <th>Is selling on equal odds paused?</th>
                                    <th>Is selling at 2X paused?</th>
                                    <th>Is selling on resistance paused?</th>
                                    <th class="text-right">Support & last % diff</th>
                                    <th class="text-right">Pump counts</th>
                                    <th class="text-right">Base volume</th>
                                    <th class="text-right">Average base volume</th>
                                    <th class="text-right">Support & equal odds ratio</th>
                                    <th class="text-right">Lowest price (Last)</th>
                                    <th class="text-right">Average price</th>
                                    <th class="text-right">Average return</th>
                                    <th class="text-right">Expected gain</th>
                                    <th class="text-right">Expected return</th>
                                    <th class="text-right">Total row count</th>
                                    <th class="text-right">Row count of support</th>
                                    <th class="text-right">Support price</th>
                                    <th class="text-right">Row count of equal odds</th>
                                    <th class="text-right">Equal odds price</th>
                                    <th class="text-right">Row count resistance</th>
                                    <th class="text-right">Resistance price</th>
                                    <th class="text-right">Resistance & equal odds ratio</th>
                                    <th class="text-right">Last price</th>
                                    <th class="text-right">Resistance & support % diff</th>
                                    <th class="text-right">Updated date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($equalOddsPricesCurrencies) > 0)
                                    <?php $i = 1; ?>
                                    @forelse ($equalOddsPricesCurrencies as $equalOddsPricesCurrency)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $equalOddsPricesCurrency->exchangeName }}</td>
                                            <td>
                                                <a href="{{URL::to('https://bittrex.com/Market/Index?MarketName=')}}{{ $equalOddsPricesCurrency->MarketName }}"
                                                   target="_blank">{{ $equalOddsPricesCurrency->MarketName }}</a>
                                            </td>
                                            <td>{{ $equalOddsPricesCurrency->isBuyingPaused }}</td>
                                            <td>{{ $equalOddsPricesCurrency->isSellingOnEqualOddsPaused }}</td>
                                            <td>{{ $equalOddsPricesCurrency->isSellingAt2XPaused }}</td>
                                            <td>{{ $equalOddsPricesCurrency->isSellingOnResistancePaused }}</td>
                                            <td class="text-right"><a
                                                        href="{{URL::to('/')}}/odds/altcoin/chart/{{ $equalOddsPricesCurrency->MarketName }}" target="_blank">{{ number_format($equalOddsPricesCurrency->supportNLastPercentageDifference, 2) }}%</a>
                                            </td>
                                            <td class="text-right">{{ $equalOddsPricesCurrency->pumpCounts }}</td>
                                            <td class="text-right">{{ number_format($equalOddsPricesCurrency->BaseVolume, 2) }}</td>
                                            <td class="text-right">{{ number_format($equalOddsPricesCurrency->averageBaseVolume, 2) }}</td>
                                            <td class="text-right">{{ number_format($equalOddsPricesCurrency->supportAndEqualOddsRatio, 2) }}</td>
                                            <td class="text-right">{{ number_format($equalOddsPricesCurrency->lowestPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($equalOddsPricesCurrency->averagePrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($equalOddsPricesCurrency->averageReturn, 2) }}
                                                %
                                            </td>
                                            <td class="text-right">{{ number_format($equalOddsPricesCurrency->expectedPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($equalOddsPricesCurrency->expectedReturn, 2) }}
                                                %
                                            </td>
                                            <td class="text-right">{{ number_format($equalOddsPricesCurrency->numberOfRows, 0) }}</td>
                                            <td class="text-right">{{ number_format($equalOddsPricesCurrency->needToCountForSupportPrice, 0) }}</td>
                                            <td class="text-right">{{ number_format($equalOddsPricesCurrency->supportPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($equalOddsPricesCurrency->needToCountForEqualOddsPrice, 0) }}</td>
                                            <td class="text-right">{{ number_format($equalOddsPricesCurrency->equalOddsPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($equalOddsPricesCurrency->needToCountForResistancePrice, 0) }}</td>
                                            <td class="text-right">{{ number_format($equalOddsPricesCurrency->resistancePrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($equalOddsPricesCurrency->supportAndResistanceOddsRatio, 2) }}</td>
                                            <td class="text-right">{{ number_format($equalOddsPricesCurrency->Last, 8) }}</td>
                                            <td class="text-right">{{ number_format($equalOddsPricesCurrency->resistanceNSupportPercentageDifference, 2) }}
                                                %
                                            </td>
                                            <td class="text-right">{{ $equalOddsPricesCurrency->updated_at }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">No market odds found</td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="10">No market odds. Please try again after sometime.</td>
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
                <p><strong>Conditions</strong></p>
                <p><span class="glyphicon glyphicon-hand-right">&nbsp;</span>No conditions</p>
            </div>
        </div>

        <div class="row">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">All currencies</h1>
                </div>

            </div>
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><a href="{{URL::to('/')}}/odds/create" class="btn btn-primary">Add
                            new</a></div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Exchange name</th>
                                    <th>Market name</th>
                                    <th>Remark</th>
                                    <th>is Buying paused?</th>
                                    <th>Is selling on equal odds paused?</th>
                                    <th>Is selling at 2X paused?</th>
                                    <th>Is selling on resistance paused?</th>
                                    <th class="text-right">Support & last % diff</th>
                                    <th class="text-right">Pump counts</th>
                                    <th class="text-right">Base volume</th>
                                    <th class="text-right">Average base volume</th>
                                    <th class="text-right">Support & equal odds ratio</th>
                                    <th class="text-right">Lowest price (Last)</th>
                                    <th class="text-right">Average price</th>
                                    <th class="text-right">Average return</th>
                                    <th class="text-right">Expected gain</th>
                                    <th class="text-right">Expected return</th>
                                    <th class="text-right">Total row count</th>
                                    <th class="text-right">Row count of support</th>
                                    <th class="text-right">Support price</th>
                                    <th class="text-right">Row count of equal odds</th>
                                    <th class="text-right">Equal odds price</th>
                                    <th class="text-right">Row count resistance</th>
                                    <th class="text-right">Resistance price</th>
                                    <th class="text-right">Resistance & equal odds ratio</th>
                                    <th class="text-right">Last price</th>
                                    <th class="text-right">Resistance & support % diff</th>
                                    <th class="text-right">Updated date</th>
                                    <th class="text-right">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($allCurrencies) > 0)
                                    <?php $i = 1; ?>
                                    @forelse ($allCurrencies as $allCurrency)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $allCurrency->exchangeName }}</td>
                                            <td>
                                                <a href="{{URL::to('https://bittrex.com/Market/Index?MarketName=')}}{{ $allCurrency->MarketName }}"
                                                   target="_blank">{{ $allCurrency->MarketName }}</a>
                                            </td>
                                            <td>{{ $allCurrency->remark }}</td>
                                            <td>{{ $allCurrency->isBuyingPaused }}</td>
                                            <td>{{ $allCurrency->isSellingOnEqualOddsPaused }}</td>
                                            <td>{{ $allCurrency->isSellingAt2XPaused }}</td>
                                            <td>{{ $allCurrency->isSellingOnResistancePaused }}</td>
                                            <td class="text-right"><a
                                                        href="{{URL::to('/')}}/odds/altcoin/chart/{{ $allCurrency->MarketName }}" target="_blank">{{ number_format($allCurrency->supportNLastPercentageDifference, 2) }}%</a>
                                            </td>
                                            <td class="text-right">{{ $allCurrency->pumpCounts }}</td>
                                            <td class="text-right">{{ number_format($allCurrency->BaseVolume, 2) }}</td>
                                            <td class="text-right">{{ number_format($allCurrency->averageBaseVolume, 2) }}</td>
                                            <td class="text-right">{{ number_format($allCurrency->supportAndEqualOddsRatio, 2) }}</td>
                                            <td class="text-right">{{ number_format($allCurrency->lowestPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($allCurrency->averagePrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($allCurrency->averageReturn, 2) }}
                                                %
                                            </td>
                                            <td class="text-right">{{ number_format($allCurrency->expectedPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($allCurrency->expectedReturn, 2) }}
                                                %
                                            </td>
                                            <td class="text-right">{{ number_format($allCurrency->numberOfRows, 0) }}</td>
                                            <td class="text-right">{{ number_format($allCurrency->needToCountForSupportPrice, 0) }}</td>
                                            <td class="text-right">{{ number_format($allCurrency->supportPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($allCurrency->needToCountForEqualOddsPrice, 0) }}</td>
                                            <td class="text-right">{{ number_format($allCurrency->equalOddsPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($allCurrency->needToCountForResistancePrice, 0) }}</td>
                                            <td class="text-right">{{ number_format($allCurrency->resistancePrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($allCurrency->supportAndResistanceOddsRatio, 2) }}</td>
                                            <td class="text-right">{{ number_format($allCurrency->Last, 8) }}</td>
                                            <td class="text-right">{{ number_format($allCurrency->resistanceNSupportPercentageDifference, 2) }}
                                                %
                                            </td>
                                            <td class="text-right">{{ $allCurrency->updated_at }}</td>
                                            <td>
                                                <a href="{{URL::to('/')}}/odds/{{ $allCurrency->id }}/edit">edit</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">No market odds found</td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="10">No market odds. Please try again after sometime.</td>
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
