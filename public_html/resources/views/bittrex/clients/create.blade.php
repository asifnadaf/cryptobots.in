@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">Add Client </h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                {{Form::open(array('action' => 'BittrexExchange\Clients\ClientsListController@store','method' => 'post', 'role' => 'form', 'invalidate' => 'invalidate'))}}
                <fieldset>
                    <div class="form-group">
                        {{ Form::text('fullName', null, array('placeholder' => 'Full name', 'class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('fullName') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('emailAddress', null, array('placeholder' => 'Email address', 'class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('emailAddress') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('mobileNumber', null, array('placeholder' => 'Mobile number', 'class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('mobileNumber') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('referrerFullName', null, array('placeholder' => 'Referrrer full name', 'class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('referrerFullName') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('referrerMobileNumber', null, array('placeholder' => 'Referrer mobile number', 'class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('referrerMobileNumber') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('remark', null, array('placeholder' => 'Remark', 'class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('remark') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('apiKey', null, array('placeholder' => 'API key', 'class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('apiKey') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('secretKey', null, array('placeholder' => 'Secret key', 'class' => 'form-control')) }}
                        <label class="help-block">{{ $errors->first('secretKey') }}</label>
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
