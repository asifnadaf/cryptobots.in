@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">Add - New Market </h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                {{Form::open(array('action' => 'BittrexExchange\MarketOddsController@store','method' => 'post', 'role' => 'form', 'invalidate' => 'invalidate'))}}
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
                        <label for="isBuyingPaused">Pause buying?</label>
                        {{ Form::select('isBuyingPaused', $pauseTradingOptions, null, array('placeholder' => 'Select option', 'class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('isBuyingPaused') }}</label>
                    </div>
                    <div class="form-group">
                        <label for="isSellingOnEqualOddsPaused">Pause selling at equal odds price?</label>
                        {{ Form::select('isSellingOnEqualOddsPaused', $pauseTradingOptions, null, array('placeholder' => 'Select option', 'class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('isSellingOnEqualOddsPaused') }}</label>
                    </div>
                    <div class="form-group">
                        <label for="isSellingAt2XPaused">Pause selling at 2X price?</label>
                        {{ Form::select('isSellingAt2XPaused', $pauseTradingOptions, null, array('placeholder' => 'Select option', 'class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('isSellingAt2XPaused') }}</label>
                    </div>
                    <div class="form-group">
                        <label for="isSellingOnResistancePaused">Is selling on resistance paused?</label>
                        {{ Form::select('isSellingOnResistancePaused', $pauseTradingOptions, null, array('placeholder' => 'Select option', 'class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('isSellingOnResistancePaused') }}</label>
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
