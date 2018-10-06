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
            <h1 class="page-header">Payment Receipts</h1>
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
                    <a href="{{URL::to('/')}}/clients/payment/{{ $clientData->id }}/create" class="btn btn-primary">Add New Payment</a>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Payment date</th>
                                    <th>Client Id</th>
                                    <th>Full name</th>
                                    <th>BTC value</th>
                                    <th>Payment reference</th>
                                    <th>remark</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($paymentReceipts) > 0)
                                <?php $i=1; ?>
                            	@forelse ($paymentReceipts as $paymentReceipt)
	                                <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ Carbon\Carbon::parse($paymentReceipt->paymentDate)->format('d-m-Y') }}</td>
	                                    <td >{{ $paymentReceipt->clientId }}</td>
	                                    <td>{{ $paymentReceipt->clientFullName }}</td>
                                        <td>{{ $paymentReceipt->btcValue }}</td>
                                        <td>{{ $paymentReceipt->paymentReference }}</td>
                                        <td>{{ $paymentReceipt->remark }}</td>
                                        <td><a href="{{URL::to('/')}}/clients/payment/{{ $clientData->id }}/{{ $paymentReceipt->id }}/edit">edit</a></td>
	                                </tr>
                                @empty
                                	<tr>
	                                    <td colspan="8">No payment receipt found</td>
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
