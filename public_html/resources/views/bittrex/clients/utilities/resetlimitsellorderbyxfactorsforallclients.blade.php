@extends('layouts.index')

@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">Reset All Sell Limit Orders for all Clients</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                {!! Form::open( array('action' => ['BittrexExchange\Clients\ClientsUtilitiesController@resetSellLimitOrdersByXFactorForAllClientsUpdate'], 'method' => 'PATCH')) !!}
                <fieldset>
                    <div class="form-group">
                        <label class="alert alert-info fade in">The system changes selling limit open orders of all clients. Please enter selling limit factor. For example if you want to increase the selling price to two times enter 2.
                            To sell at 3 times the purchase price enter 3. The system takes the value you have entered and multiplies it with current ask price of the coin. </label>
                        {{ Form::text('multiplicationFactor', null, array('placeholder' => 'Limit Selling factor for open selling orders', 'class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('multiplicationFactor') }}</label>
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

