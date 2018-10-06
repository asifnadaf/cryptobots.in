@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">Edit - Buy Settings </h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">

                {!! Form::model($setting, ['method' => 'PATCH','route' => ['buysetting.update', $setting->id]]) !!}
                <fieldset>
                    <div class="form-group">
                        <label for="supportAndEqualOddsRatio">Support and equal odds ratio</label>
                        {{ Form::text('supportAndEqualOddsRatio', $setting->supportAndEqualOddsRatio, array('placeholder' => 'Support and equal odds ratio', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        <label class="help-block">{{ $errors->first('supportAndEqualOddsRatio') }}</label>
                    </div>
                    <div class="form-group">
                        <label for="maximumNumberOfDiversification">Maximum number of diversification</label>
                        {{ Form::text('maximumNumberOfDiversification', $setting->maximumNumberOfDiversification, array('placeholder' => 'Maximum number of diversification', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        <label class="help-block">{{ $errors->first('maximumNumberOfDiversification') }}</label>
                    </div>
                    <div class="form-group">
                        <label for="minimumVolumeOfBaseCurrencyBTC">Minimum base currency volume (BTC)</label>
                        {{ Form::text('minimumVolumeOfBaseCurrencyBTC', $setting->minimumVolumeOfBaseCurrencyBTC, array('placeholder' => 'Minimum support resistance % difference', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        <label class="help-block">{{ $errors->first('minimumVolumeOfBaseCurrencyBTC') }}</label>
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

