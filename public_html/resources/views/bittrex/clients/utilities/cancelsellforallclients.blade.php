@extends('layouts.index')

@section('content')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Sell for all clients</h1>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-6">
            {!! Form::open( array('action' => ['BittrexExchange\Clients\ClientsUtilitiesController@cancelSellForAllClients'], 'method' => 'PATCH')) !!}
            <fieldset>
                    <div class="form-group">
                        <label class="alert alert-info fade in">Please enter market name. For example BTC-ETH</label>
                        {{ Form::text('marketName', null, array('placeholder' => 'Enter market name', 'class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('marketName') }}</label>
                    </div>
                    <div class="form-group">
                        <input id="submit" name="submit" type="submit" value="Submit" class="btn btn-primary">
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
