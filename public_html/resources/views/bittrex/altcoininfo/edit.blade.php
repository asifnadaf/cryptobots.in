@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">Edit - Altcoin information </h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">

                {!! Form::model($ticker, ['method' => 'PATCH','route' => ['altcoininfo.update', $ticker->id]]) !!}
                <fieldset>
                    <div class="form-group">
                        {{ Form::text('name', ucfirst($ticker->coinMarketCapCoinName), array('placeholder' => 'Market name', 'class' => 'form-control', 'readonly' => 'true')) }}
                        <label class="help-block">{{ $errors->first('name') }}</label>
                    </div>
                    <div class="form-group">
                        {{ Form::text('remark', $ticker->remark, array('placeholder' => 'Remark', 'class' => 'form-control','autofocus' => 'autofocus')) }}
                        <label class="help-block">{{ $errors->first('remark') }}</label>
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

