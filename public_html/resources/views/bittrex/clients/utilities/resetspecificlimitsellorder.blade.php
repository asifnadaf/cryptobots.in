@extends('layouts.index')

@section('content')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Sell specific Altcoins</h1>
        </div>

    </div>

    
    <div class="row">
        <div class="col-lg-6">
            {!! Form::open( array('action' => ['BittrexExchange\Clients\ClientsUtilitiesController@resetSpecificSellLimitOrdersUpdate'], 'method' => 'PATCH')) !!}
            <fieldset>
                    <div class="form-group">
                        <label class="alert alert-info fade in">Please enter comma separated Client ids. For example, 1,2,3,4.</label>
                        {{ Form::text('clientsIdList', $allClientsIds, array('placeholder' => 'Enter comma separate Clients Ids', 'class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('clientsIdList') }}</label>
                    </div>
                    <div class="form-group">
                        <label class="alert alert-info fade in">Please enter comma separated Altcoins name. For example, BTC-ETH, BTC-XRP, BTC-ETC. The system will sell these Altcoins at available market rates </label>
                        {{ Form::text('altcoinsName', null, array('placeholder' => 'Enter comma separate Altcoins name', 'class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('altcoinsName') }}</label>
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
