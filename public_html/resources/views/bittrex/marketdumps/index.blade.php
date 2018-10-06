@extends('layouts.index')

@section('content')
<div id="page-wrapper">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Market Dumps</h1>
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
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Exchange name</th>
                                    <th>Market name</th>
                                    <th>Dump percentage (%)</th>
                                    <th>Volume (in BTC)</th>
                                    <th>High</th>
                                    <th>Last</th>
                                    <th>Previous day</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($marketDumpsData) > 0)
                                <?php $i=1; ?>
                            	@forelse ($marketDumpsData as $row)
	                                <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ Carbon\Carbon::parse($row->TimeStamp)->format('d-m-Y') }}</td>
	                                    <td>{{ $row->exchangeName }}
                                        <td><a href="{{URL::to('https://bittrex.com/Market/Index?MarketName=')}}{{ $row->marketName }}" target="_blank">{{ $row->marketName }}</a>
                                        <td>{{ number_format($row->percentChange, 2) }}</td>
                                        <td>{{ number_format($row->BaseVolume, 2) }}</td>
                                        <td>{{ number_format($row->High, 8) }}</td>
                                        <td>{{ number_format($row->Last, 8) }}</td>
                                        <td>{{ number_format($row->PrevDay, 8) }}</td>
                                    </tr>
                                @empty
                                	<tr>
	                                    <td colspan="10">No trackers found</td>
	                            	</tr>
                                @endforelse
                                @else
                                    <tr>
                                        <td colspan="10">No dumps. Please try again after sometime.</td>
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
