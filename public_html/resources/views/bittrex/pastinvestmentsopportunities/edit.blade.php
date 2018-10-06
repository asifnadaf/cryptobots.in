@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">Edit - Past Investments Opportunities </h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">

                {!! Form::model($resistancePrice, ['method' => 'PATCH','route' => ['opportunities.update', $resistancePrice->id]]) !!}
                <fieldset>
                    <div class="form-group">
                        {{ Form::text('exchangeName', $resistancePrice->exchangeName, array('placeholder' => 'Exchange Name', 'class' => 'form-control', 'readonly' => 'readonly')) }}
                        <label class="help-block">{{ $errors->first('exchangeName') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('marketName', $resistancePrice->MarketName, array('placeholder' => 'Market Name', 'class' => 'form-control','autofocus' => 'autofocus', 'readonly' => 'readonly')) }}
                        {{ Form::hidden('resistancePriceId', $resistancePrice->id) }}
                        <label class="help-block">{{ $errors->first('marketName') }}</label>
                    </div>

                    <div class="form-group">
                        <label for="isSellingOnResistancePaused">Pause selling on resistance price?</label>
                        {{ Form::select('isSellingOnResistancePaused', $isSellingOnResistancePaused, $resistancePrice->isSellingOnResistancePaused, array('placeholder' => 'Select option', 'class' => 'form-control')) }}
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
