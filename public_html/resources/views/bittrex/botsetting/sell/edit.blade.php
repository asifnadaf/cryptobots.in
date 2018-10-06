@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">Edit - Sell Settings </h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">

                {!! Form::model($setting, ['method' => 'PATCH','route' => ['sellsetting.update', $setting->id]]) !!}
                <fieldset>
                    <div class="form-group">
                        <label for="pumpFactor">Pump factor</label>
                        {{ Form::text('pumpFactor', $setting->pumpFactor, array('placeholder' => 'Pump factor', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        <label class="help-block">{{ $errors->first('pumpFactor') }}</label>
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

