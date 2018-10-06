@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">Edit - Altcoin </h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">

                {!! Form::model($altcoinData, ['method' => 'PATCH','route' => ['altcoinssettings.update', $altcoinData->id]]) !!}
                <fieldset>
                    <div class="form-group">
                        {{ Form::text('exchangeName', $altcoinData->exchangeName, array('placeholder' => 'Exchange Name', 'class' => 'form-control', 'readonly' => 'readonly')) }}
                        <label class="help-block">{{ $errors->first('exchangeName') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('marketName', $altcoinData->MarketName, array('placeholder' => 'Market Name', 'class' => 'form-control','autofocus' => 'autofocus', 'readonly' => 'readonly')) }}
                        {{ Form::hidden('altcoinDataId', $altcoinData->id) }}
                        <label class="help-block">{{ $errors->first('marketName') }}</label>
                    </div>
                    <div class="form-group">
                        <label for="isBuyingPaused">Pause buying?</label>
                        {{ Form::select('isBuyingPaused', $pauseTradingOptions, $altcoinData->isBuyingPaused, array('placeholder' => 'Select option', 'class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('isBuyingPaused') }}</label>
                    </div>
                    <div class="form-group">
                        <label for="isSellingOnResistancePaused">Pause selling at resistance price?</label>
                        {{ Form::select('isSellingOnResistancePaused', $pauseTradingOptions, $altcoinData->isSellingOnResistancePaused, array('placeholder' => 'Select option', 'class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('isSellingOnResistancePaused') }}</label>
                    </div>
                    <div class="form-group">
                        <label for="isSellingAt2XPaused">Pause selling at 2X price?</label>
                        {{ Form::select('isSellingAt2XPaused', $pauseTradingOptions, $altcoinData->isSellingAt2XPaused, array('placeholder' => 'Select option', 'class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('isSellingAt2XPaused') }}</label>
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
