@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Update Koinex Tickers Volume</h1>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        BCC
                    </div>
                    <div class="panel-body">
                        {{Form::open(array('action' => 'BittrexKoinexArbitrage\ArbitrageOverviewController@bccBuyVolumeOrderBook','method' => 'post', 'role' => 'form', 'invalidate' => 'invalidate'))}}
                        <fieldset>
                            <div class="form-group">
                                <label for="koinexBCCBuyVolume">Buy Volume</label>
                                {{ Form::text('koinexBCCBuyVolume', null, array('class' => 'form-control','autofocus' => 'autofocus')) }}
                                <label class="help-block">{{ $errors->first('koinexBCCBuyVolume') }}</label>
                            </div>
                            <div class="form-group">
                                <label for="koinexBCCBuyPrice">Buy Price</label>
                                {{ Form::text('koinexBCCBuyPrice', null, array('class' => 'form-control','autofocus' => 'autofocus')) }}
                                <label class="help-block">{{ $errors->first('koinexBCCBuyPrice') }}</label>
                            </div>
                            <div class="form-group">
                                <input id="submit" name="submit" type="submit" value="Update" class="btn btn-primary">
                                @if(!empty($error))
                                    <label class="help-block">{{ $error }}</label>
                                @endif
                            </div>
                        </fieldset>
                        {{Form::close()}}
                    </div>
                </div>
            </div>


            <div class="col-lg-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        ETH
                    </div>
                    <div class="panel-body">
                        {{Form::open(array('action' => 'BittrexKoinexArbitrage\ArbitrageOverviewController@ethBuyVolumeOrderBook','method' => 'post', 'role' => 'form', 'invalidate' => 'invalidate'))}}
                        <fieldset>
                            <div class="form-group">
                                <label for="koinexETHBuyVolume">Buy Volume</label>
                                {{ Form::text('koinexETHBuyVolume', null, array('class' => 'form-control','autofocus' => 'autofocus')) }}
                                <label class="help-block">{{ $errors->first('koinexETHBuyVolume') }}</label>
                            </div>
                            <div class="form-group">
                                <label for="koinexETHBuyPrice">Buy Price</label>
                                {{ Form::text('koinexETHBuyPrice', null, array('class' => 'form-control','autofocus' => 'autofocus')) }}
                                <label class="help-block">{{ $errors->first('koinexETHBuyPrice') }}</label>
                            </div>
                            <div class="form-group">
                                <input id="submit" name="submit" type="submit" value="Update" class="btn btn-primary">
                                @if(!empty($error))
                                    <label class="help-block">{{ $error }}</label>
                                @endif
                            </div>
                        </fieldset>
                        {{Form::close()}}
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        LTC
                    </div>
                    <div class="panel-body">
                        {{Form::open(array('action' => 'BittrexKoinexArbitrage\ArbitrageOverviewController@ltcBuyVolumeOrderBook','method' => 'post', 'role' => 'form', 'invalidate' => 'invalidate'))}}
                        <fieldset>
                            <div class="form-group">
                                <label for="koinexLTCBuyVolume">Buy Volume</label>
                                {{ Form::text('koinexLTCBuyVolume', null, array('class' => 'form-control','autofocus' => 'autofocus')) }}
                                <label class="help-block">{{ $errors->first('koinexLTCBuyVolume') }}</label>
                            </div>
                            <div class="form-group">
                                <label for="koinexLTCBuyPrice">Buy Price</label>
                                {{ Form::text('koinexLTCBuyPrice', null, array('class' => 'form-control','autofocus' => 'autofocus')) }}
                                <label class="help-block">{{ $errors->first('koinexLTCBuyPrice') }}</label>
                            </div>
                            <div class="form-group">
                                <input id="submit" name="submit" type="submit" value="Update" class="btn btn-primary">
                                @if(!empty($error))
                                    <label class="help-block">{{ $error }}</label>
                                @endif
                            </div>
                        </fieldset>
                        {{Form::close()}}
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        XRP
                    </div>
                    <div class="panel-body">
                        {{Form::open(array('action' => 'BittrexKoinexArbitrage\ArbitrageOverviewController@xrpBuyVolumeOrderBook','method' => 'post', 'role' => 'form', 'invalidate' => 'invalidate'))}}
                        <fieldset>
                            <div class="form-group">
                                <label for="koinexXRPBuyVolume">Buy Volume</label>
                                {{ Form::text('koinexXRPBuyVolume', null, array('class' => 'form-control','autofocus' => 'autofocus')) }}
                                <label class="help-block">{{ $errors->first('koinexXRPBuyVolume') }}</label>
                            </div>
                            <div class="form-group">
                                <label for="koinexXRPBuyPrice">Buy Price</label>
                                {{ Form::text('koinexXRPBuyPrice', null, array('class' => 'form-control','autofocus' => 'autofocus')) }}
                                <label class="help-block">{{ $errors->first('koinexXRPBuyPrice') }}</label>
                            </div>
                            <div class="form-group">
                                <input id="submit" name="submit" type="submit" value="Update" class="btn btn-primary">
                                @if(!empty($error))
                                    <label class="help-block">{{ $error }}</label>
                                @endif
                            </div>
                        </fieldset>
                        {{Form::close()}}
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        BTC
                    </div>
                    <div class="panel-body">
                        {{Form::open(array('action' => 'BittrexKoinexArbitrage\ArbitrageOverviewController@btcBuyVolumeOrderBook','method' => 'post', 'role' => 'form', 'invalidate' => 'invalidate'))}}
                        <fieldset>
                            <div class="form-group">
                                <label for="koinexBTCBuyVolume">Buy Volume</label>
                                {{ Form::text('koinexBTCBuyVolume', null, array('class' => 'form-control','autofocus' => 'autofocus')) }}
                                <label class="help-block">{{ $errors->first('koinexBTCBuyVolume') }}</label>
                            </div>
                            <div class="form-group">
                                <label for="koinexBTCBuyPrice">Buy Price</label>
                                {{ Form::text('koinexBTCBuyPrice', null, array('class' => 'form-control','autofocus' => 'autofocus')) }}
                                <label class="help-block">{{ $errors->first('koinexBTCBuyPrice') }}</label>
                            </div>
                            <div class="form-group">
                                <input id="submit" name="submit" type="submit" value="Update" class="btn btn-primary">
                                @if(!empty($error))
                                    <label class="help-block">{{ $error }}</label>
                                @endif
                            </div>
                        </fieldset>
                        {{Form::close()}}
                    </div>
                </div>
            </div>

        </div>


        <div class="row">
            <div class="col-lg-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        BCC
                    </div>
                    <div class="panel-body">
                        {{Form::open(array('action' => 'BittrexKoinexArbitrage\ArbitrageOverviewController@bccSellVolumeOrderBook','method' => 'post', 'role' => 'form', 'invalidate' => 'invalidate'))}}
                        <fieldset>
                            <div class="form-group">
                                <label for="koinexBCCSellVolume">Sell Volume</label>
                                {{ Form::text('koinexBCCSellVolume', null, array('class' => 'form-control','autofocus' => 'autofocus')) }}
                                <label class="help-block">{{ $errors->first('koinexBCCSellVolume') }}</label>
                            </div>
                            <div class="form-group">
                                <label for="koinexBCCSellPrice">Sell Price</label>
                                {{ Form::text('koinexBCCSellPrice', null, array('class' => 'form-control','autofocus' => 'autofocus')) }}
                                <label class="help-block">{{ $errors->first('koinexBCCSellPrice') }}</label>
                            </div>
                            <div class="form-group">
                                <input id="submit" name="submit" type="submit" value="Update" class="btn btn-primary">
                                @if(!empty($error))
                                    <label class="help-block">{{ $error }}</label>
                                @endif
                            </div>
                        </fieldset>
                        {{Form::close()}}
                    </div>
                </div>
            </div>


            <div class="col-lg-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        ETH
                    </div>
                    <div class="panel-body">
                        {{Form::open(array('action' => 'BittrexKoinexArbitrage\ArbitrageOverviewController@ethSellVolumeOrderBook','method' => 'post', 'role' => 'form', 'invalidate' => 'invalidate'))}}
                        <fieldset>
                            <div class="form-group">
                                <label for="koinexETHSellVolume">Sell Volume</label>
                                {{ Form::text('koinexETHSellVolume', null, array('class' => 'form-control','autofocus' => 'autofocus')) }}
                                <label class="help-block">{{ $errors->first('koinexETHSellVolume') }}</label>
                            </div>
                            <div class="form-group">
                                <label for="koinexETHSellPrice">Sell Price</label>
                                {{ Form::text('koinexETHSellPrice', null, array('class' => 'form-control','autofocus' => 'autofocus')) }}
                                <label class="help-block">{{ $errors->first('koinexETHSellPrice') }}</label>
                            </div>
                            <div class="form-group">
                                <input id="submit" name="submit" type="submit" value="Update" class="btn btn-primary">
                                @if(!empty($error))
                                    <label class="help-block">{{ $error }}</label>
                                @endif
                            </div>
                        </fieldset>
                        {{Form::close()}}
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        LTC
                    </div>
                    <div class="panel-body">
                        {{Form::open(array('action' => 'BittrexKoinexArbitrage\ArbitrageOverviewController@ltcSellVolumeOrderBook','method' => 'post', 'role' => 'form', 'invalidate' => 'invalidate'))}}
                        <fieldset>
                            <div class="form-group">
                                <label for="koinexLTCSellVolume">Sell Volume</label>
                                {{ Form::text('koinexLTCSellVolume', null, array('class' => 'form-control','autofocus' => 'autofocus')) }}
                                <label class="help-block">{{ $errors->first('koinexLTCSellVolume') }}</label>
                            </div>
                            <div class="form-group">
                                <label for="koinexLTCSellPrice">Sell Price</label>
                                {{ Form::text('koinexLTCSellPrice', null, array('class' => 'form-control','autofocus' => 'autofocus')) }}
                                <label class="help-block">{{ $errors->first('koinexLTCSellPrice') }}</label>
                            </div>
                            <div class="form-group">
                                <input id="submit" name="submit" type="submit" value="Update" class="btn btn-primary">
                                @if(!empty($error))
                                    <label class="help-block">{{ $error }}</label>
                                @endif
                            </div>
                        </fieldset>
                        {{Form::close()}}
                    </div>
                </div>
            </div>


            <div class="col-lg-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        XRP
                    </div>
                    <div class="panel-body">
                        {{Form::open(array('action' => 'BittrexKoinexArbitrage\ArbitrageOverviewController@xrpSellVolumeOrderBook','mxrpod' => 'post', 'role' => 'form', 'invalidate' => 'invalidate'))}}
                        <fieldset>
                            <div class="form-group">
                                <label for="koinexXRPSellVolume">Sell Volume</label>
                                {{ Form::text('koinexXRPSellVolume', null, array('class' => 'form-control','autofocus' => 'autofocus')) }}
                                <label class="help-block">{{ $errors->first('koinexXRPSellVolume') }}</label>
                            </div>
                            <div class="form-group">
                                <label for="koinexXRPSellPrice">Sell Price</label>
                                {{ Form::text('koinexXRPSellPrice', null, array('class' => 'form-control','autofocus' => 'autofocus')) }}
                                <label class="help-block">{{ $errors->first('koinexXRPSellPrice') }}</label>
                            </div>
                            <div class="form-group">
                                <input id="submit" name="submit" type="submit" value="Update" class="btn btn-primary">
                                @if(!empty($error))
                                    <label class="help-block">{{ $error }}</label>
                                @endif
                            </div>
                        </fieldset>
                        {{Form::close()}}
                    </div>
                </div>
            </div>


            <div class="col-lg-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        BTC
                    </div>
                    <div class="panel-body">
                        {{Form::open(array('action' => 'BittrexKoinexArbitrage\ArbitrageOverviewController@btcSellVolumeOrderBook','method' => 'post', 'role' => 'form', 'invalidate' => 'invalidate'))}}
                        <fieldset>
                            <div class="form-group">
                                <label for="koinexBTCSellVolume">Sell Volume</label>
                                {{ Form::text('koinexBTCSellVolume', null, array('class' => 'form-control','autofocus' => 'autofocus')) }}
                                <label class="help-block">{{ $errors->first('koinexBTCSellVolume') }}</label>
                            </div>
                            <div class="form-group">
                                <label for="koinexBTCSellPrice">Sell Price</label>
                                {{ Form::text('koinexBTCSellPrice', null, array('class' => 'form-control','autofocus' => 'autofocus')) }}
                                <label class="help-block">{{ $errors->first('koinexBTCSellPrice') }}</label>
                            </div>
                            <div class="form-group">
                                <input id="submit" name="submit" type="submit" value="Update" class="btn btn-primary">
                                @if(!empty($error))
                                    <label class="help-block">{{ $error }}</label>
                                @endif
                            </div>
                        </fieldset>
                        {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>


    </div>
@stop
