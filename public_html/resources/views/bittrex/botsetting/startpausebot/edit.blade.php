@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">Edit - Start / Pause Bots Settings </h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">

                {!! Form::model($setting, ['method' => 'PATCH','route' => ['startpausebotsetting.update', $setting->id]]) !!}
                <fieldset>
                    <div class="form-group">
                        <label for="pauseBuyAtSupportBot">Pause buy at support bot</label><label class="color-red">&nbsp;*</label>
                        {{ Form::select('pauseBuyAtSupportBot', $pauseBuyAtSupportBot, $setting->pauseBuyAtSupportBot, array('class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('pauseBuyAtSupportBot') }}</label>
                    </div>

                    <div class="form-group">
                        <label for="pauseSellOnResistancePriceBot">Pause sell on resistance price bot?</label><label class="color-red">&nbsp;*</label>
                        {{ Form::select('pauseSellOnResistancePriceBot', $pauseSellOnResistancePriceBot, $setting->pauseSellOnResistancePriceBot, array('class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('pauseSellOnResistancePriceBot') }}</label>
                    </div>

                    <div class="form-group">
                        <label for="pauseUpdateSellLimitOrderBookToXTimesBot">Pause update sell limit orderbook to X times bot?</label><label class="color-red">&nbsp;*</label>
                        {{ Form::select('pauseUpdateSellLimitOrderBookToXTimesBot', $pauseUpdateSellLimitOrderBookToXTimesBot, $setting->pauseUpdateSellLimitOrderBookToXTimesBot, array('class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('pauseUpdateSellLimitOrderBookToXTimesBot') }}</label>
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

