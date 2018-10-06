@extends('layouts.index')
@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Past Market Statistics</h1>
            </div>

        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>Past Market Statistics</b>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Market name</th>
                                    <th>days</th>
                                    <th>Pumps 200%</th>
                                    <th>Pumps 100%</th>
                                    <th>Pumps 50%</th>
                                    <th>Pumps 30%</th>
                                    <th>Dumps 50%</th>
                                    <th>Dumps 33.33%</th>
                                    <th>Listed price</th>
                                    <th>Current price</th>
                                    <th>% Change</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($data) > 0)
                                    <?php $i = 1; ?>
                                    @forelse ($data as $row)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td><a href="{{URL::to('https://bittrex.com/Market/Index?MarketName=')}}{{ $row['marketName'] }}" target="_blank">{{ $row['marketName'] }}</a>
                                            <td>{{ $row['numberOfDays'] }}</td>
                                            <td>{{ $row['numberOfPumps200'] }}</td>
                                            <td>{{ $row['numberOfPumps100'] }}</td>
                                            <td>{{ $row['numberOfPumps50'] }}</td>
                                            <td>{{ $row['numberOfPumps30'] }}</td>
                                            <td>{{ $row['numberOfDumps50'] }}</td>
                                            <td>{{ $row['numberOfDumps33'] }}</td>

                                            <td>{{ number_format($row['listedPrice'], 8) }}</td>
                                            <td>{{ number_format($row['currentPrice'], 8) }}</td>
                                            <td>{{ number_format($row['percentChange'], 2) }}</td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">No data found</td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="10">No data. Please try again after sometime.</td>
                                    </tr>
                                @endif

                                <tr>
                                    <td>{{$summary['marketsCount']}}</td>
                                    <td></td>
                                    <td>{{$summary['daysCoinsCount']}}</td>
                                    <td>{{ $summary['allNumberOfPumps200'] }}</td>
                                    <td>{{ $summary['allNumberOfPumps100'] }}</td>
                                    <td>{{ $summary['allNumberOfPumps50'] }}</td>
                                    <td>{{ $summary['allNumberOfPumps30'] }}</td>
                                    <td>{{ $summary['allNumberOfDumps50'] }}</td>
                                    <td>{{ $summary['allNumberOfDumps33'] }}</td>

                                    <td>{{ number_format($summary['allListedPrice'], 8) }}</td>
                                    <td>{{ number_format($summary['allCurrentPrice'], 8) }}</td>
                                    <td>{{ number_format($summary['allPercentReturn'], 2) }}</td>

                                </tr>


                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
@stop
