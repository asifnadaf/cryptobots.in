@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">Edit - Support & Resistance </h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">

                {!! Form::model($supportPrice, ['method' => 'PATCH','route' => ['support.update', $supportPrice->id]]) !!}
                <fieldset>
                    <div class="form-group">
                        {{ Form::text('exchangeName', $supportPrice->exchangeName, array('placeholder' => 'Exchange Name', 'class' => 'form-control', 'readonly' => 'readonly')) }}
                        <label class="help-block">{{ $errors->first('exchangeName') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('marketName', $supportPrice->MarketName, array('placeholder' => 'Market Name', 'class' => 'form-control','autofocus' => 'autofocus', 'readonly' => 'readonly')) }}
                        {{ Form::hidden('supportPriceId', $supportPrice->id) }}
                        <label class="help-block">{{ $errors->first('marketName') }}</label>
                    </div>
                    <div class="form-group">
                        <label for="reasonForPauseDate">Date on which currency trading is paused</label>
                        {{ Form::text('reasonForPauseDate', $supportPrice->reasonForPauseDate, array('placeholder' => 'Date of pause', 'class' => 'form-control','autofocus' => 'autofocus', 'readonly' => 'readonly')) }}
                        <label class="help-block">{{ $errors->first('reasonForPauseDate') }}</label>
                    </div>
                    <div class="form-group">
                        <label for="pauseTrading">Pause trading in this currency?</label>
                        {{ Form::select('pauseTrading', $pauseTrading, $supportPrice->pauseTrading, array('placeholder' => 'Select option', 'class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('pauseTrading') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('reasonForPause', $supportPrice->reasonForPause, array('placeholder' => 'Reason for pausing trade on this currency', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        <label class="help-block">{{ $errors->first('reasonForPause') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
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
