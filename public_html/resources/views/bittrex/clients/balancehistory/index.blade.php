@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize text-center">Client name: {{ $clientData->fullName }}</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">Balance History</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <h4 class="text-capitalize">Altcoins
                    Balances: {{number_format($accountBalance['totalBalanceIn_BTC'], 8 )}} BTC / {{number_format($accountBalance['totalBalanceIn_USDT'], 8 )}} USDT</h4>
            </div>
        </div>

        <div class="row">
            @if($accountError!=null)
                <div class="alert alert-danger" role="alert">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span>
                    {{$accountError}}
                </div>
        @endif

        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th class="text-left">#</th>
                                    <th class="text-left">Date</th>
                                    <th class="text-right">Available Balance in BTC</th>
                                    <th class="text-right">USDT Rate</th>
                                    <th class="text-right">Available Balance in USDT</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($balanceHistory) > 0)
                                    <?php $i=1; ?>
                                    @forelse ($balanceHistory as $row)
                                        <tr>
                                            <td class="text-left">{{ $i++ }}</td>
                                            <td class="text-left">{{Carbon\Carbon::parse($row->created_at,'UCT')->format('d-m-Y') }}</td>
                                            <td class="text-right">{{ number_format($row->btcBalanceValue, 8) }}</td>
                                            <td class="text-right">{{ number_format($row->BTCToTetherRate, 2) }}</td>
                                            <td class="text-right">{{ number_format($row->usdtBalanceValue, 2 ) }} </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">You have no account balance.</td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="10">No account balance found. Please try again after sometime.</td>
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
