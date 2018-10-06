@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Bittrex BTC Index</h1>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>Current prices</b>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>Yesterday's Index price</th>
                                    <th>Today's Index price</th>
                                    <th>% difference Index</th>

                                    <th>Yesterday's BTC price</th>
                                    <th>Today's BTC price</th>
                                    <th>% difference BTC</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{ number_format($currentData->sumOf24HoursBackPriceBittrexIndex, 2) }}</td>
                                    <td>{{ number_format($currentData->sumOfCurrentPriceBittrexIndex, 2) }}</td>
                                    <td>{{ number_format($currentData->percentageDifferenceBittrexIndex, 2) }}</td>

                                    <td>{{ number_format($currentData->twentyFourHoursBackPriceBTC, 2) }}</td>
                                    <td>{{ number_format($currentData->CurrentPriceBTC, 2) }}</td>
                                    <td>{{ number_format($currentData->percentageDifferenceBTC, 2) }}</td>
                                </tr>
                                </tbody>
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
                        <b>Recent prices</b>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Index size</th>
                                    <th>Yesterday's Index price</th>
                                    <th>Today's Index price</th>

                                    <th>Yesterday's BTC price</th>
                                    <th>Today's BTC price</th>

                                    <th>Yesterday's Product price (000's)</th>
                                    <th>Today's Product price (000's)</th>

                                    <th>% difference Index</th>
                                    <th>% difference BTC</th>
                                    <th>% difference Product</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($bittrexBTCIndexData) > 0)
                                    <?php $i = 1; ?>
                                    @forelse ($bittrexBTCIndexData as $row)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td class="text-left">{{ Carbon\Carbon::parse($row->created_at,'UCT')->format('d-m-Y h:i:s A') }}</td>
                                            <td>{{ number_format($row->indexSize, 0) }}</td>
                                            <td>{{ number_format($row->sumOf24HoursBackPriceBittrexIndex, 2) }}</td>
                                            <td>{{ number_format($row->sumOfCurrentPriceBittrexIndex, 2) }}</td>

                                            <td>{{ number_format($row->twentyFourHoursBackPriceBTC, 2) }}</td>
                                            <td>{{ number_format($row->CurrentPriceBTC, 2) }}</td>

                                            <td>{{ number_format($row->twentyFourHoursBackProduct, 2) }}</td>
                                            <td>{{ number_format($row->CurrentPriceProduct, 2) }}</td>

                                            <td>{{ number_format($row->percentageDifferenceBittrexIndex, 2) }}</td>
                                            <td>{{ number_format($row->percentageDifferenceBTC, 2) }}</td>
                                            <td>{{ number_format($row->percentageDifferenceProduct, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">No Bittrex BTC Index found</td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="10">No Bittrex BTC Index. Please try again after sometime.</td>
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
