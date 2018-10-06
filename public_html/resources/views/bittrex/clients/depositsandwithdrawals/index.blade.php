@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize text-center">Client name: {{ $clientData->fullName }}</h1>
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
                <h1 class="page-header text-capitalize">Deposits and Withdrawals</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <h4 class="text-capitalize">Altcoins
                    Balances: {{number_format($accountBalance['totalBalanceIn_BTC'], 8 )}} BTC / {{number_format($accountBalance['totalBalanceIn_USDT'], 8 )}} USDT</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>Deposits History</b>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th class="text-left">#</th>
                                    <th class="text-left">Date</th>
                                    <th class="text-left">Currency</th>
                                    <th class="text-right">Unit</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($depositHistory) > 0)
                                    <?php $i = 1; ?>
                                    @forelse ($depositHistory as $dh)
                                        <tr>
                                            <td class="text-left">{{ $i++ }}</td>
                                            <td class="text-left">{{ Carbon\Carbon::parse($dh->LastUpdated,'UCT')->setTimezone('Asia/Kolkata')->format('d-m-Y h:i:s A') }}</td>
                                            <td class="text-left">{{ $dh->Currency }}</td>
                                            <td class="text-right">{{ number_format($dh->Amount, 8 ) }} </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">You have no deposit history.</td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="10">No deposit history found. Please try again after sometime.</td>
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
                        <b>Withdrawals History</b>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th class="text-left">#</th>
                                        <th class="text-left">Date</th>
                                        <th class="text-left">Currency</th>
                                        <th class="text-right">Unit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($withdrawalHistory) > 0)
                                        <?php $i = 1; ?>
                                        @forelse ($withdrawalHistory as $wh)
                                            <tr>
                                                <td class="text-left">{{ $i++ }}</td>
                                                <td class="text-left">{{ Carbon\Carbon::parse($wh->Opened,'UCT')->setTimezone('Asia/Kolkata')->format('d-m-Y h:i:s A') }}</td>
                                                <td class="text-left">{{ $wh->Currency }}</td>
                                                <td class="text-right">{{ number_format($wh->Amount, 8 ) }} </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10">You have no withdrawal history.</td>
                                            </tr>
                                        @endforelse
                                    @else
                                        <tr>
                                            <td colspan="10">No withdrawal history found. Please try again after sometime.
                                            </td>
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

    </div>


    </div>
@stop
