@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Add - Payment Receipt</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                {{Form::open(array('action' => 'BittrexExchange\Clients\PaymentReceiptController@store','method' => 'post', 'role' => 'form', 'invalidate' => 'invalidate'))}}
                <fieldset>
                    <div class="form-group">
                        {{ Form::text('clientId', $clientList->id, array('placeholder' => 'Client Id', 'class' => 'form-control','autofocus' => 'autofocus', 'readonly' => 'readonly')) }}
                        <label class="help-block">{{ $errors->first('clientId') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('clientFullName', $clientList->fullName, array('placeholder' => 'Client full name', 'class' => 'form-control','autofocus' => 'autofocus', 'readonly' => 'readonly')) }}
                        <label class="help-block">{{ $errors->first('clientFullName') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('paymentDate', null, array('placeholder' => 'Payment date (format 2017-01-31)', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        <label class="help-block">{{ $errors->first('paymentDate') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('btcValue', null, array('placeholder' => 'BTC value', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        <label class="help-block">{{ $errors->first('btcValue') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('paymentReference',null, array('placeholder' => 'Payment reference (BTC deposit address reference)', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        <label class="help-block">{{ $errors->first('paymentReference') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('remark', null, array('placeholder' => 'Remark', 'class' => 'form-control','autofocus' => 'autofocus'))}}
                        <label class="help-block">{{ $errors->first('remark') }}</label>
                    </div>
                    <div class="form-group">
                        <input id="submit" name="submit" type="submit" value="Save" class="btn btn-primary">
                        @if(!empty($error))
                            <label class="help-block">{{ $error }}</label>
                        @endif
                    </div>
                </fieldset>
                {{Form::close()}}
            </div>
        </div>

    </div>
@stop
