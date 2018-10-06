@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">Edit - Arbitrage Settings </h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">

                {!! Form::model($setting, ['method' => 'PATCH','route' => ['kbas.update', $setting->id]]) !!}
                <fieldset>
                    <div class="form-group">
                        <label for="minimumTradeSize">Minimum trade size</label>
                        {{ Form::text('minimumTradeSize', $setting->minimumTradeSize, array('placeholder' => 'Minimum trade size', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        <label class="help-block">{{ $errors->first('minimumTradeSize') }}</label>
                    </div>
                    <div class="form-group">
                        <label for="maximumTradeSize">Maximum trade size</label>
                        {{ Form::text('maximumTradeSize', $setting->maximumTradeSize, array('placeholder' => 'Maximum trade size', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        <label class="help-block">{{ $errors->first('maximumTradeSize') }}</label>
                    </div>
                    <div class="form-group">
                        <label for="minimumGrossPercentGain">Minimum gross percent gain</label>
                        {{ Form::text('minimumGrossPercentGain', $setting->minimumGrossPercentGain, array('placeholder' => 'Minimum gross percent gain', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        <label class="help-block">{{ $errors->first('minimumGrossPercentGain') }}</label>
                    </div>

                    <div class="form-group">
                        <label for="">Check Koinex ticker volume updated timestamp?</label>
                        {{ Form::select('lookAtKoinexTickerVolumeUpdateTimestamp', $lookAtKoinexTickerVolumeUpdateTimestampOptions, $setting->lookAtKoinexTickerVolumeUpdateTimestamp, array('class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('lookAtKoinexTickerVolumeUpdateTimestamp') }}</label>
                    </div>

                    <div class="form-group">
                        <label for="bittrexBidAboveLowestAskByPercent">Bittrex bid above lowest ask by percent</label>
                        {{ Form::text('bittrexBidAboveLowestAskByPercent', $setting->bittrexBidAboveLowestAskByPercent, array('placeholder' => 'Bittrex bid above lowest ask by percent', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        <label class="help-block">{{ $errors->first('bittrexBidAboveLowestAskByPercent') }}</label>
                    </div>
                    <div class="form-group">
                        <label for="bittrexAskBelowHighestBidByPercent">Bittrex ask below highest bid by percent</label>
                        {{ Form::text('bittrexAskBelowHighestBidByPercent', $setting->bittrexAskBelowHighestBidByPercent, array('placeholder' => 'Bittrex ask below highest bid by percent', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        <label class="help-block">{{ $errors->first('bittrexAskBelowHighestBidByPercent') }}</label>
                    </div>

                    <div class="form-group">
                        <label for="koinexBidAboveLowestAskByPercent">Koinex bid above lowest ask by percent</label>
                        {{ Form::text('koinexBidAboveLowestAskByPercent', $setting->koinexBidAboveLowestAskByPercent, array('placeholder' => 'Koinex bid above lowest ask by percent', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        <label class="help-block">{{ $errors->first('koinexBidAboveLowestAskByPercent') }}</label>
                    </div>
                    <div class="form-group">
                        <label for="koinexAskBelowHighestBidByPercent">Koinex ask below highest bid by percent</label>
                        {{ Form::text('koinexAskBelowHighestBidByPercent', $setting->koinexAskBelowHighestBidByPercent, array('placeholder' => 'Koinex ask below lowest ask by percent', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        <label class="help-block">{{ $errors->first('koinexAskBelowHighestBidByPercent') }}</label>
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

