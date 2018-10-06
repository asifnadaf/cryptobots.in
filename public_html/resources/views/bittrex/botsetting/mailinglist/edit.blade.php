@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">Edit - Mailing List </h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">

                {!! Form::model($setting, ['method' => 'PATCH','route' => ['mailinglistsetting.update', $setting->id]]) !!}
                <fieldset>
                    <div class="form-group">
                        {{ Form::text('fullName', $setting->fullName, array('placeholder' => 'Full name', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        <label class="help-block">{{ $errors->first('fullName') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('emailAddress', $setting->emailAddress, array('placeholder' => 'Email address', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        {{ Form::hidden('clientListId', $setting->id) }}
                        <label class="help-block">{{ $errors->first('emailAddress') }}</label>
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

