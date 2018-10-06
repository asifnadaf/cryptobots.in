@extends('layouts.index')

@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Trading Accounts</h1>
            </div>
        </div>
        <div class="row padding-bottom-10">
            <div class="col-lg-1">
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" data-hover="dropdown">
                        Actions <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="{{URL::to('/')}}/clients/create">Add new Client</a></li>
                        <li><a href="{{URL::to('/')}}/clients/reset/specific/sell/limit/orders">Sell specific Altcoins for specific clients</a></li>
                        <li><a href="{{URL::to('/')}}/clients/sell/all/altcoins/single/client">Sell all Altcoins of single client</a></li>
                        <li class="dropdown">
                            <a href="#">For all clients</a>
                            <ul class="dropdown-menu">
                                <li><a href="{{URL::to('/')}}/clients/reset/sell/limit/orders/all/clients">Reset Sell Orders for all clients</a></li>
                                <li><a href="{{URL::to('/')}}/clients/buy/for/all/clients">Buy for all clients</a></li>
                                <li><a href="{{URL::to('/')}}/clients/cancel/buy/for/all/clients">Cancel - Buy for all clients</a></li>
                                <li><a href="{{URL::to('/')}}/clients/sell/for/all/clients">Sell for all clients</a></li>
                                <li><a href="{{URL::to('/')}}/clients/cancel/sell/for/all/clients">Cancel - Sell for all clients</a></li>
                                <li><a href="{{URL::to('/')}}/clients/pauseallclients">Pause all Accounts</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#">USDT Market</a>
                            <ul class="dropdown-menu">
                                <li><a href="{{URL::to('/')}}/clients/sell/tether/for/all/clients">Buy Altcoin / Sell USDT</a></li>
                                <li><a href="{{URL::to('/')}}/clients/cancel/sell/tether/for/all/clients">Cancel - Buy Altcoin / Sell USDT</a></li>
                                <li><a href="{{URL::to('/')}}/clients/buy/tether/for/all/clients">Sell Altcoin / Buy USDT</a></li>
                                <li><a href="{{URL::to('/')}}/clients/cancel/buy/tether/for/all/clients">Cancel - Sell Altcoin / Buy USDT</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-8">
            </div>
            <div class="col-lg-3 text-right">
                <p><strong>1 BTC = {{number_format($thetherBTCRate,2)}} USDT  = {{number_format($usdBTCRate,2)}} USD = {{number_format($INRBTCRate,2)}} INR</strong></p>
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
                                    <th>Client Id</th>
                                    <th>Full name</th>
                                    <th>Email address</th>
                                    <th>Mobile number</th>
                                    <th>Is Trading Paused?</th>
                                    <th>Trading exchange</th>
                                    <th>Cryptocurrencies balances</th>
                                    <th>Order history</th>
                                    <th>BTC balance history</th>
                                    <th>Deposits & withdrawals</th>
                                    <th>Payment receipts</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($clientslist) > 0)
                                    <?php $i=1; ?>
                                    @forelse ($clientslist as $row)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td >{{ $row->id }}</td>
                                            <td>{{ $row->fullName }}</td>
                                            <td>{{ $row->emailAddress }}</td>
                                            <td>{{ $row->mobileNumber }}</td>
                                            <td>{{ $row->pauseTrading }}</td>
                                            <td><a href="{{URL::to('/')}}/clients/exchange/{{ $row->id }}" target="_blank">Trading exchange</a></td>
                                            <td><a href="{{URL::to('/')}}/clients/{{ $row->id }}/crypto/balance" target="_blank">Cryptocurrencies balances</a></td>
                                            <td><a href="{{URL::to('/')}}/clients/{{ $row->id }}/order/history" target="_blank">Order History</a></td>
                                            <td><a href="{{URL::to('/')}}/clients/{{ $row->id }}/btc/balance/history" target="_blank">BTC balance history</a></td>
                                            <td><a href="{{URL::to('/')}}/clients/{{ $row->id }}/deposits/withdrawals" target="_blank">Deposits & withdrawals</a></td>
                                            <td><a href="{{URL::to('/')}}/clients/payment/{{ $row->id }}" target="_blank">Payment receipts</a></td>
                                            <td><a href="{{URL::to('/')}}/clients/{{ $row->id }}/edit/">edit</a></td>

                                    @empty
                                        <tr>
                                            <td colspan="8">No clients found</td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="8">No data feed available. Please try again after sometime</td>
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
