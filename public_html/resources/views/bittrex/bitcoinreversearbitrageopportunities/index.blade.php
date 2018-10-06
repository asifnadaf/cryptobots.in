@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">International reverse arbitrage opportunities</h1>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>Current opportunity</b>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>Zebpay INR rate (selling rate)</th>
                                    <th>Blockchain INR rate</th>
                                    <th>Localbitcoins INR rate</th>

                                    <th>BlockChain Zebpay Profit %</th>
                                    <th>BlockChain Zebpay Profit in INR</th>
                                    {{--<th>Localbitcoins country</th>--}}
                                    <th>Localbitcoins Zebpay Profit %</th>
                                    <th>Localbitcoins Zebpay Profit in INR</th>

                                </tr>
                                </thead>
                                <tbody>
                                @if(count($currentData) > 0 )
                                    <tr>
                                        <td>{{ number_format($currentData['zeppayINRRate'], 2) }}</td>
                                        <td>{{ number_format($currentData['blockchainINRRate'], 2) }}</td>
                                        <td>{{ number_format($currentData['temp_price_inr'], 2) }}</td>

                                        <td>{{ number_format($currentData['percentageDifferenceBlockchainZebpay'], 2) }}</td>
                                        <td>{{ number_format($currentData['profitBlockchainZebpay'], 2) }}</td>

                                        {{--<td>{{ $currentData['location_string'] }}</td>--}}
                                        <td>{{ number_format($currentData['percentageDifferenceLocalBitcoinsZebpay'], 2) }}</td>
                                        <td>{{ number_format($currentData['profitLocalBitcoinsZebpay'], 2) }}</td>

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
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>Past opportunities</b>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>

                                    <th>Zebpay INR rate (buying rate)</th>

                                    <th>Blockchain INR rate</th>
                                    <th>BlockChain Zebpay Profit %</th>
                                    <th>BlockChain Zebpay Profit in INR</th>

                                    {{--<th>Localbitcoins country</th>--}}
                                    {{--<th>Localbitcoins INR rate</th>--}}
                                    {{--<th>Localbitcoins Zebpay Profit %</th>--}}
                                    {{--<th>Localbitcoins Zebpay Profit in INR</th>--}}

                                </tr>
                                </thead>
                                <tbody>
                                @if(count($bitcoinArbitrageOpportunitiesData) > 0)
                                    <?php $i = 1; ?>
                                    @forelse ($bitcoinArbitrageOpportunitiesData as $row)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ Carbon\Carbon::parse($row->updated_at)->format('d-m-Y h:i:s A') }}</td>

                                            <td>{{ number_format($row->zeppayINRRate, 2) }}</td>

                                            <td>{{ number_format($row->blockchainINRRate, 2) }}</td>
                                            <td>{{ number_format($row->percentageDifferenceBlockchainZebpay, 2) }}</td>
                                            <td>{{ number_format($row->profitBlockchainZebpay, 2) }}</td>

                                            {{--<td>{{ $row->location_string }}</td>--}}
                                            {{--<td>{{ number_format($row->temp_price_inr, 2) }}</td>--}}
                                            {{--<td>{{ number_format($row->percentageDifferenceLocalBitcoinsZebpay, 2) }}</td>--}}
                                            {{--<td>{{ number_format($row->profitLocalBitcoinsZebpay, 2) }}</td>--}}

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
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>
@stop
