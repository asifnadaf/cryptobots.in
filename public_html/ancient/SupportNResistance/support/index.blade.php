@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Support & Resistance Prices</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <p style="background: #FFECBC; "><span
                            class="glyphicon glyphicon-hand-right">&nbsp;</span>Currency trading is paused</p>
                <p style="background: #CEDDBE; "><span
                            class="glyphicon glyphicon-hand-right">&nbsp;</span>Current market's Ask price is below
                    Support price & base BTC volume >= Bot setting defined value & base Avg Support Resistance %
                    difference >= Bot setting defined value </p>
                <p style="background: #FFF2F2; "><span class="glyphicon glyphicon-hand-right">&nbsp;</span>Current
                    market's Bid price is above Resistance price</p>
                <p><span class="glyphicon glyphicon-hand-right">&nbsp;</span>Current market's Last price is between
                    Support and Resistance price</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="{{URL::to('/')}}/support/create" class="btn btn-primary">Add</a>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Exchange name</th>
                                    <th>Market name</th>
                                    <th>Paused?</th>
                                    <th class="text-right">(LP - ASP)/ASP%</th>
                                    <th class="text-right">(ARP - ASP)/ASP%</th>
                                    <th class="text-right">(ARP - LP)/LP%</th>
                                    <th class="text-right">Volume (in BTC)</th>
                                    <th class="text-right">Support Range (Low)</th>
                                    <th class="text-right">Average Support price (ASP)</th>
                                    <th class="text-right">Support Strength</th>
                                    <th class="text-right">Support Count</th>

                                    <th class="text-right">Resistance Range (Open)</th>
                                    <th class="text-right">Average Resistance price (ASP)</th>
                                    <th class="text-right">Resistance Strength</th>
                                    <th class="text-right">Resistance Count</th>
                                    <th class="text-right">Last price (LP)</th>
                                    <th class="text-right">Last updated</th>
                                    <th class="text-right">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($supportAndResistancePrices) > 0)
                                    <?php $i = 1; ?>
                                    @forelse ($supportAndResistancePrices as $supportAndResistancePrice)
                                        @if(strcasecmp($supportAndResistancePrice->pauseTrading, 'Yes') == 0)
                                            <tr style="background: #FFECBC; ">
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $supportAndResistancePrice->exchangeName }}</td>
                                                <td>
                                                    <a style="background: #FFECBC; "
                                                       href="{{URL::to('https://bittrex.com/Market/Index?MarketName=')}}{{ $supportAndResistancePrice->MarketName }}"
                                                       target="_blank">{{ $supportAndResistancePrice->MarketName }}</a>
                                                </td>
                                                <td>{{ $supportAndResistancePrice['pauseTrading'] }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->avgSupportNLastdifference, 2) }}
                                                    %
                                                </td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->averageSupportAndAverageResistancePercentageDifference, 2) }}
                                                    %
                                                </td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->avgResistanceNLastdifference, 2) }}
                                                    %
                                                </td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->BaseVolume, 2) }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->minValue, 8) }}
                                                    - {{ number_format($supportAndResistancePrice->supportThreshold, 8) }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->averageSupportPrice, 8) }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->supportStrength, 2) }}
                                                    %
                                                </td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->supportCount, 0) }}
                                                    /{{ number_format($supportAndResistancePrice->totalSupportCount, 0) }}</td>

                                                <td class="text-right">{{ number_format($supportAndResistancePrice->maxValue, 8) }}
                                                    - {{ number_format($supportAndResistancePrice->resistanceThreshold, 8) }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->averageResistancePrice, 8) }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->resistanceStrength, 2) }}
                                                    %
                                                </td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->resistanceCount, 0) }}
                                                    /{{ number_format($supportAndResistancePrice->totalResistanceCount, 0) }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->Last, 8) }}</td>
                                                <td class="text-right">{{ $supportAndResistancePrice->updated_at }}</td>
                                                <td>
                                                    <a style="background: #FFECBC"
                                                       href="{{URL::to('/')}}/support/{{ $supportAndResistancePrice->id }}/edit">edit</a>
                                                </td>
                                            </tr>@elseif($supportAndResistancePrice->currentPriceAboveResistancePrice == true )
                                            <tr style="background: #FFF2F2; ">
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $supportAndResistancePrice->exchangeName }}</td>
                                                <td>
                                                    <a style="background: #FFF2F2; "
                                                       href="{{URL::to('https://bittrex.com/Market/Index?MarketName=')}}{{ $supportAndResistancePrice->MarketName }}"
                                                       target="_blank">{{ $supportAndResistancePrice->MarketName }}</a>
                                                </td>
                                                <td>{{ $supportAndResistancePrice['pauseTrading'] }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->avgSupportNLastdifference, 2) }}
                                                    %
                                                </td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->averageSupportAndAverageResistancePercentageDifference, 2) }}
                                                    %
                                                </td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->avgResistanceNLastdifference, 2) }}
                                                    %
                                                </td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->BaseVolume, 2) }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->minValue, 8) }}
                                                    - {{ number_format($supportAndResistancePrice->supportThreshold, 8) }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->averageSupportPrice, 8) }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->supportStrength, 2) }}
                                                    %
                                                </td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->supportCount, 0) }}
                                                    /{{ number_format($supportAndResistancePrice->totalSupportCount, 0) }}</td>

                                                <td class="text-right">{{ number_format($supportAndResistancePrice->maxValue, 8) }}
                                                    - {{ number_format($supportAndResistancePrice->resistanceThreshold, 8) }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->averageResistancePrice, 8) }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->resistanceStrength, 2) }}
                                                    %
                                                </td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->resistanceCount, 0) }}
                                                    /{{ number_format($supportAndResistancePrice->totalResistanceCount, 0) }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->Last, 8) }}</td>
                                                <td class="text-right">{{ $supportAndResistancePrice->updated_at }}</td>
                                                <td>
                                                    <a style="background: #FFF2F2"
                                                       href="{{URL::to('/')}}/support/{{ $supportAndResistancePrice->id }}/edit">edit</a>
                                                </td>
                                            </tr>
                                        @elseif($supportAndResistancePrice->currentPriceBelowSupportPrice == true )
                                            <tr style="background: #CEDDBE; ">
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $supportAndResistancePrice->exchangeName }}</td>
                                                <td>
                                                    <a style="background: #CEDDBE; "
                                                       href="{{URL::to('https://bittrex.com/Market/Index?MarketName=')}}{{ $supportAndResistancePrice->MarketName }}"
                                                       target="_blank">{{ $supportAndResistancePrice->MarketName }}</a>
                                                </td>
                                                <td>{{ $supportAndResistancePrice['pauseTrading'] }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->avgSupportNLastdifference, 2) }}
                                                    %
                                                </td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->averageSupportAndAverageResistancePercentageDifference, 2) }}
                                                    %
                                                </td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->avgResistanceNLastdifference, 2) }}
                                                    %
                                                </td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->BaseVolume, 2) }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->minValue, 8) }}
                                                    - {{ number_format($supportAndResistancePrice->supportThreshold, 8) }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->averageSupportPrice, 8) }} </td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->supportStrength, 2) }}
                                                    %
                                                </td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->supportCount, 0) }}
                                                    /{{ number_format($supportAndResistancePrice->totalSupportCount, 0) }}</td>

                                                <td class="text-right">{{ number_format($supportAndResistancePrice->maxValue, 8) }}
                                                    - {{ number_format($supportAndResistancePrice->resistanceThreshold, 8) }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->averageResistancePrice, 8) }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->resistanceStrength, 2) }}
                                                    %
                                                </td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->resistanceCount, 0) }}
                                                    /{{ number_format($supportAndResistancePrice->totalResistanceCount, 0) }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->Last, 8) }}</td>
                                                <td class="text-right">{{ $supportAndResistancePrice->updated_at }}</td>
                                                <td>
                                                    <a style="background: #CEDDBE"
                                                       href="{{URL::to('/')}}/support/{{ $supportAndResistancePrice->id }}/edit">edit</a>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $supportAndResistancePrice->exchangeName }}</td>
                                                <td>
                                                    <a href="{{URL::to('https://bittrex.com/Market/Index?MarketName=')}}{{ $supportAndResistancePrice->MarketName }}"
                                                       target="_blank">{{ $supportAndResistancePrice->MarketName }}</a>
                                                </td>
                                                <td>{{ $supportAndResistancePrice['pauseTrading'] }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->avgSupportNLastdifference, 2) }}
                                                    %
                                                </td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->averageSupportAndAverageResistancePercentageDifference, 2) }}
                                                    %
                                                </td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->avgResistanceNLastdifference, 2) }}
                                                    %
                                                </td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->BaseVolume, 2) }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->minValue, 8) }}
                                                    - {{ number_format($supportAndResistancePrice->supportThreshold, 8) }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->averageSupportPrice, 8) }} </td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->supportStrength, 2) }}
                                                    %
                                                </td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->supportCount, 0) }}
                                                    /{{ number_format($supportAndResistancePrice->totalSupportCount, 0) }}</td>

                                                <td class="text-right">{{ number_format($supportAndResistancePrice->maxValue, 8) }}
                                                    - {{ number_format($supportAndResistancePrice->resistanceThreshold, 8) }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->averageResistancePrice, 8) }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->resistanceStrength, 2) }}
                                                    %
                                                </td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->resistanceCount, 0) }}
                                                    /{{ number_format($supportAndResistancePrice->totalResistanceCount, 0) }}</td>
                                                <td class="text-right">{{ number_format($supportAndResistancePrice->Last, 8) }}</td>
                                                <td class="text-right">{{ $supportAndResistancePrice->updated_at }}</td>
                                                <td>
                                                    <a href="{{URL::to('/')}}/support/{{ $supportAndResistancePrice->id }}/edit">edit</a>
                                                </td>
                                            </tr>
                                        @endif

                                    @empty
                                        <tr>
                                            <td colspan="10">No trackers found</td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="10">No pumps. Please try again after sometime.</td>
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
