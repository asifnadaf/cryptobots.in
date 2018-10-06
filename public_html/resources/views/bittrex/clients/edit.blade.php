@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">Edit Client </h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">

                {!! Form::model($clientList, ['method' => 'PATCH','route' => ['clients.update', $clientList->id]]) !!}
                <fieldset>
                    <div class="form-group">
                        {{ Form::text('fullName', $clientList->fullName, array('placeholder' => 'Full name', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        {{ Form::hidden('clientListId', $clientList->id) }}
                        <label class="help-block">{{ $errors->first('fullName') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('emailAddress', $clientList->emailAddress, array('placeholder' => 'Email address', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        <label class="help-block">{{ $errors->first('emailAddress') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('mobileNumber', $clientList->mobileNumber, array('placeholder' => 'Mobile number', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        <label class="help-block">{{ $errors->first('mobileNumber') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('referrerFullName', $clientList->referrerFullName, array('placeholder' => 'Referrrer full name', 'class' => 'form-control','autofocus' => 'autofocus', 'readonly' => 'true')) }}
                        <label class="help-block">{{ $errors->first('referrerFullName') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('referrerMobileNumber', $clientList->referrerMobileNumber, array('placeholder' => 'Referrer mobile number', 'class' => 'form-control','autofocus' => 'autofocus', 'readonly' => 'true')) }}
                        <label class="help-block">{{ $errors->first('referrerMobileNumber') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('remark', $clientList->remark, array('placeholder' => 'Remark', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        <label class="help-block">{{ $errors->first('remark') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('apiKey', $clientList->apiKey, array('placeholder' => 'API key', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        <label class="help-block">{{ $errors->first('apiKey') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('secretKey', $clientList->secretKey, array('placeholder' => 'Secret key', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        <label class="help-block">{{ $errors->first('secretKey') }}</label>
                    </div>
                    <div class="form-group">
                        <label for="pauseTrading">Pause trading?</label><label class="color-red">&nbsp;*</label>
                        {{ Form::select('pauseTrading', $pauseTrading, $clientList->pauseTrading, array('placeholder' => 'Pause trading?', 'class' => 'form-control')) }}
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

