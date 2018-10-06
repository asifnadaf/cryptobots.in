@extends('layouts.index')

@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Arbitrage Opportunities</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">

                <div class="text-center">
                    {{ $data->links() }}
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>Opportunities</b>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Timestamp</th>
                                    <th>Opportunity type</th>
                                    <th>Sell transaction value</th>
                                    <th>Buy transaction value</th>
                                    <th>Gross % gain</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($data) > 0)
                                    <?php $i = 1; ?>
                                    @forelse ($data as $row)
                                        <tr>
                                            <td>{{  $i++  }}</td>
                                            <td>{{ Carbon\Carbon::parse($row->updated_at)->format('d-m-Y h:i:s A') }}</td>
                                            <td>{{ $row->opportunityType }}</td>
                                            <td>{{ $row->sellTransactionValue }}</td>
                                            <td>{{ $row->buyTransactionValue }}</td>
                                            <td>{{ bcdiv($row->grossPercentGain, 1, 2)}}</td>
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
                            </table>
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>
@stop
