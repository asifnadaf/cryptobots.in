@extends('layouts.index')

@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Cancel - Sell Altcoin / Buy USDT Tether</h1>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-6">
                {!! Form::open( array('action' => ['BittrexExchange\Clients\USDTMarketController@cancelBuyTether'], 'method' => 'PATCH')) !!}
                <fieldset>
                    <div class="form-group">
                        <label class="alert alert-info fade in">Please enter comma separated Client ids. For example,
                            1,2,3,4.</label>
                        {{ Form::text('clientsIdList', $allClientsIds, array('placeholder' => 'Enter comma separate Clients Ids', 'class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('clientsIdList') }}</label>
                    </div>
                    <div class="form-group">
                        <label class="alert alert-info fade in">Market name (For example USDT-ZEC)</label>
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
