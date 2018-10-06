@extends('layouts.index')

@section('content')
<div id="page-wrapper">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header text-capitalize">Add - Support & Resistance </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            {{Form::open(array('action' => 'BittrexExchange\SupportResistanceController@store','method' => 'post', 'role' => 'form', 'invalidate' => 'invalidate'))}}
                <fieldset>

                    <div class="form-group">
                        {{ Form::text('exchangeName', null, array('placeholder' => 'Exchange Name', 'class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('exchangeName') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('marketName', null, array('placeholder' => 'Market Name', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        <label class="help-block">{{ $errors->first('marketName') }}</label>
                    </div>
                    <div class="form-group">
                        <label for="pauseTrading">Pause trading in this currency?</label><label class="color-red">&nbsp;*</label>
                        {{ Form::select('pauseTrading', $pauseTrading, null, array('placeholder' => 'Select option', 'class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('pauseTrading') }}</label>
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
