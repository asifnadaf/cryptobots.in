@extends('layouts.index')

@section('content')
<div id="page-wrapper">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Market Delisting</h1>
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
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>MarketName</th>

                                    <th>MinTradeSize</th>
                                    <th>IsActive</th>

                                    <th>Created</th>
                                    <th>Notice</th>
                                    <th>MarketCurrency</th>

                                    <th>BaseCurrency</th>
                                </tr>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($marketDelistingData) > 0)
                                <?php $i=1; ?>
                            	@forelse ($marketDelistingData as $row)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td class="text-left">{{ Carbon\Carbon::parse($row->created_at,'UCT') }}</td>
                                        <td><a href="{{URL::to('https://bittrex.com/Market/Index?MarketName=')}}{{ $row->MarketName }}" target="_blank">{{ $row->MarketName }}</a>

                                        <td>{{ number_format($row->MinTradeSize, 8) }}</td>
                                        <td>{{ $row->IsActive }}

                                        <td>{{ $row->Created }}
                                        <td>{{ $row->Notice }}
                                        <td>{{ $row->MarketCurrency }}

                                        <td>{{ $row->BaseCurrency }}

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
