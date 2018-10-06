@extends('layouts.index')

@section('content')
    <div id="page-wrapper">


        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Past Investments Opportunities</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>Resistance Prices</b>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Exchange name</th>
                                    <th>Market name</th>
                                    <th>Position</th>
                                    <th>Order type</th>
                                    <th>Was Buying paused?</th>
                                    <th>Was selling on equal odds paused?</th>
                                    <th>Was selling at 2X paused?</th>
                                    <th>Was selling on resistance paused?</th>
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
                                    <th class="text-right">Support & equal odds ratio</th>
                                    <th class="text-right">Row count resistance</th>
                                    <th class="text-right">Resistance price</th>
                                    <th class="text-right">Resistance & equal odds ratio</th>
                                    <th class="text-right">Last price</th>
                                    <th class="text-right">Support & last % diff</th>
                                    <th class="text-right">Resistance & support % diff</th>
                                    <th class="text-right">Updated date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($resistancePricesCurrencies) > 0)
                                    <?php $i = 1; ?>
                                    @forelse ($resistancePricesCurrencies as $resistancePricesCurrency)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $resistancePricesCurrency->exchangeName }}</td>
                                            <td>
                                                <a href="{{URL::to('https://bittrex.com/Market/Index?MarketName=')}}{{ $resistancePricesCurrency->MarketName }}"
                                                   target="_blank">{{ $resistancePricesCurrency->MarketName }}</a>
                                            </td>
                                            <td>{{ $resistancePricesCurrency->position }}</td>
                                            <td>{{ $resistancePricesCurrency->orderType }}</td>

                                            <td>{{ $resistancePricesCurrency->isBuyingPaused }}</td>
                                            <td>{{ $resistancePricesCurrency->isSellingOnEqualOddsPaused }}</td>
                                            <td>{{ $resistancePricesCurrency->isSellingAt2XPaused }}</td>
                                            <td>{{ $resistancePricesCurrency->isSellingOnResistancePaused }}</td>

                                            <td class="text-right">{{ number_format($resistancePricesCurrency->lowestPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($resistancePricesCurrency->averagePrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($resistancePricesCurrency->averageReturn, 2) }}
                                                %
                                            </td>
                                            <td class="text-right">{{ number_format($resistancePricesCurrency->expectedPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($resistancePricesCurrency->expectedReturn, 2) }}
                                                %
                                            </td>
                                            <td class="text-right">{{ number_format($resistancePricesCurrency->numberOfRows, 0) }}</td>
                                            <td class="text-right">{{ number_format($resistancePricesCurrency->needToCountForSupportPrice, 0) }}</td>
                                            <td class="text-right">{{ number_format($resistancePricesCurrency->supportPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($resistancePricesCurrency->needToCountForEqualOddsPrice, 0) }}</td>
                                            <td class="text-right">{{ number_format($resistancePricesCurrency->equalOddsPrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($resistancePricesCurrency->supportAndEqualOddsRatio, 2) }}</td>
                                            <td class="text-right">{{ number_format($resistancePricesCurrency->needToCountForResistancePrice, 0) }}</td>
                                            <td class="text-right">{{ number_format($resistancePricesCurrency->resistancePrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($resistancePricesCurrency->supportAndResistanceOddsRatio, 2) }}</td>
                                            <td class="text-right">{{ number_format($resistancePricesCurrency->Last, 8) }}</td>
                                            <td class="text-right">{{ number_format($resistancePricesCurrency->supportNLastPercentageDifference, 2) }}
                                                %
                                            </td>
                                            <td class="text-right">{{ number_format($resistancePricesCurrency->resistanceNSupportPercentageDifference, 2) }}
                                                %
                                            </td>
                                            <td class="text-right">{{ $resistancePricesCurrency->updated_at }}</td>
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
                            </table>
                        </div>

                    </div>

                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>Support Prices</b>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Exchange name</th>
                                    <th>Market name</th>
                                    <th>Position</th>
                                    <th>Order type</th>
                                    <th>Was Buying paused?</th>
                                    <th>Was selling on equal odds paused?</th>
                                    <th>Was selling at 2X paused?</th>
                                    <th>Was selling on resistance paused?</th>
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
                                    <th class="text-right">Support & equal odds ratio</th>
                                    <th class="text-right">Row count resistance</th>
                                    <th class="text-right">Resistance price</th>
                                    <th class="text-right">Resistance & equal odds ratio</th>
                                    <th class="text-right">Last price</th>
                                    <th class="text-right">Support & last % diff</th>
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
                                            <td>{{ $supportPricesCurrency->position }}</td>
                                            <td>{{ $supportPricesCurrency->orderType }}</td>

                                            <td>{{ $supportPricesCurrency->isBuyingPaused }}</td>
                                            <td>{{ $supportPricesCurrency->isSellingOnEqualOddsPaused }}</td>
                                            <td>{{ $supportPricesCurrency->isSellingAt2XPaused }}</td>
                                            <td>{{ $supportPricesCurrency->isSellingOnResistancePaused }}</td>

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
                                            <td class="text-right">{{ number_format($supportPricesCurrency->supportAndEqualOddsRatio, 2) }}</td>
                                            <td class="text-right">{{ number_format($supportPricesCurrency->needToCountForResistancePrice, 0) }}</td>
                                            <td class="text-right">{{ number_format($supportPricesCurrency->resistancePrice, 8) }}</td>
                                            <td class="text-right">{{ number_format($supportPricesCurrency->supportAndResistanceOddsRatio, 2) }}</td>
                                            <td class="text-right">{{ number_format($supportPricesCurrency->Last, 8) }}</td>
                                            <td class="text-right">{{ number_format($supportPricesCurrency->supportNLastPercentageDifference, 2) }}
                                                %
                                            </td>
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


    </div>
@stop
