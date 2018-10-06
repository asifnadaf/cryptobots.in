@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">&nbsp;&nbsp;
            </div>
        </div>


        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>
                            @if($result['BCCBittrexUpKoinexDown']['isArbitrageOpportunity'] == true)
                                <b>BCCBittrexUpKoinexDownYes</b> -
                            @else
                                <b>BCCBittrexUpKoinexDownNo</b> -
                            @endif
                            @if(!empty($result['BCCBittrexUpKoinexDown']['data']))
                                delay of {{$result['BCCBittrexUpKoinexDown']['data']['timestamp']}} secs - returns
                                of {{$result['BCCBittrexUpKoinexDown']['data']['returns']}}%
                            @endif
                        </span>
                    </div>
                    <div class="panel-body" style="min-height: 130px; max-height: 135px;">
                        @if(!empty($result['message']))
                            <div>{{ $result['message'] }} </div>
                            <div>&nbsp;</div>
                            <div>&nbsp;</div>

                        @elseif(!empty($result['BCCBittrexUpKoinexDown']['message']))

                            <div>{{ $result['BCCBittrexUpKoinexDown']['message'] }}</div>

                        @elseif(!empty($result['BCCBittrexUpKoinexDown']['isArbitrageOpportunity']))

                            <div class="input-group" style="margin-bottom: 10px">
                                <span class="input-group-addon">1. Buy</span>
                                <input type="text" class="form-control"
                                       value="{{$result['BCCBittrexUpKoinexDown']['data']['INR-BCC']['Quantity']}}"/>
                                <span class="input-group-addon">units of BCC @ Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['BCCBittrexUpKoinexDown']['data']['INR-BCC']['Rate']}}"/>
                                <span class="input-group-addon">per unit to spend a total of Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['BCCBittrexUpKoinexDown']['data']['INR-BCC']['Amount'] }}"/>
                                <span class="input-group-addon">in Koinex</span>
                            </div>

                            <div class="input-group" style="margin-bottom: 10px">
                                <span class="input-group-addon">2. Sell</span>
                                <input type="text" class="form-control"
                                       value="{{$result['BCCBittrexUpKoinexDown']['data']['INR-BTC']['Quantity']}}"/>
                                <span class="input-group-addon">units of BTC @ Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['BCCBittrexUpKoinexDown']['data']['INR-BTC']['Rate']}}"/>
                                <span class="input-group-addon">per unit to get a total of Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['BCCBittrexUpKoinexDown']['data']['INR-BTC']['Amount'] }}"/>
                                <span class="input-group-addon">in Koinex</span>
                            </div>

                            <div class="input-group" style="margin-bottom: 10px; padding-left: 13px">
                                <a href="{{URL::to('/')}}/kpas/koinex/arbitrage/sell/on/bittrex/BTC-BCC/{{ $result['BCCBittrexUpKoinexDown']['data']['BTC-BCC']['Rate'] }} / {{$result['BCCBittrexUpKoinexDown']['data']['BTC-BCC']['Quantity']}}">
                                    3.
                                    Sell {{$result['BCCBittrexUpKoinexDown']['data']['BTC-BCC']['Quantity']}}
                                    units of BCC @
                                    BTC. {{$result['BCCBittrexUpKoinexDown']['data']['BTC-BCC']['Rate']}}
                                    per unit to get a total of
                                    BTC. {{$result['BCCBittrexUpKoinexDown']['data']['BTC-BCC']['Amount'] }}
                                    in Bittrex
                                </a>
                            </div>

                        @else
                            <div>No arbitrage opportunity</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>
                            @if($result['BCCKoinexUpBittrexDown']['isArbitrageOpportunity'] == true)
                                <b>BCCKoinexUpBittrexDownYes</b> -
                            @else
                                <b>BCCKoinexUpBittrexDownNo</b> -
                            @endif
                            @if(!empty($result['BCCKoinexUpBittrexDown']['data']))
                                delay of {{$result['BCCKoinexUpBittrexDown']['data']['timestamp']}} secs - returns
                                of {{$result['BCCKoinexUpBittrexDown']['data']['returns']}}%
                            @endif
                        </span>
                    </div>
                    <div class="panel-body" style="min-height: 130px; max-height: 135px;">
                        @if(!empty($result['message']))
                            <div>{{ $result['message'] }} </div>
                            <div>&nbsp;</div>
                            <div>&nbsp;</div>

                        @elseif(!empty($result['BCCKoinexUpBittrexDown']['message']))

                            <div>{{ $result['BCCKoinexUpBittrexDown']['message'] }}</div>

                        @elseif(!empty($result['BCCKoinexUpBittrexDown']['isArbitrageOpportunity']))

                            <div class="input-group" style="margin-bottom: 10px">
                                <span class="input-group-addon">1. Sell</span>
                                <input type="text" class="form-control"
                                       value="{{$result['BCCKoinexUpBittrexDown']['data']['INR-BCC']['Quantity']}}"/>
                                <span class="input-group-addon">units of BCC @ Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['BCCKoinexUpBittrexDown']['data']['INR-BCC']['Rate']}}"/>
                                <span class="input-group-addon">per unit to get a total of Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['BCCKoinexUpBittrexDown']['data']['INR-BCC']['Amount'] }}"/>
                                <span class="input-group-addon">in Koinex</span>
                            </div>

                            <div class="input-group" style="margin-bottom: 10px">
                                <span class="input-group-addon">2. Buy</span>
                                <input type="text" class="form-control"
                                       value="{{$result['BCCKoinexUpBittrexDown']['data']['INR-BTC']['Quantity']}}"/>
                                <span class="input-group-addon">units of BTC @ Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['BCCKoinexUpBittrexDown']['data']['INR-BTC']['Rate']}}"/>
                                <span class="input-group-addon">per unit to spend a total of Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['BCCKoinexUpBittrexDown']['data']['INR-BTC']['Amount'] }}"/>
                                <span class="input-group-addon">in Koinex</span>
                            </div>

                            <div class="input-group" style="margin-bottom: 10px; padding-left: 13px">
                                <a href="{{URL::to('/')}}/kpas/koinex/arbitrage/sell/on/bittrex/BTC-BCC/{{ $result['BCCKoinexUpBittrexDown']['data']['BTC-BCC']['Rate'] }} / {{$result['BCCKoinexUpBittrexDown']['data']['BTC-BCC']['Quantity']}}">
                                    3.
                                    Buy {{$result['BCCKoinexUpBittrexDown']['data']['BTC-BCC']['Quantity']}}
                                    units of BCC @
                                    BTC. {{$result['BCCKoinexUpBittrexDown']['data']['BTC-BCC']['Rate']}}
                                    per unit to spend a total of
                                    BTC. {{$result['BCCKoinexUpBittrexDown']['data']['BTC-BCC']['Amount'] }}
                                    in Bittrex
                                </a>
                            </div>

                        @else
                            <div>No arbitrage opportunity</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>
                            @if($result['ETHBittrexUpKoinexDown']['isArbitrageOpportunity'] == true)
                                <b>ETHBittrexUpKoinexDownYes</b> -
                            @else
                                <b>ETHBittrexUpKoinexDownNo</b> -
                            @endif
                            @if(!empty($result['ETHBittrexUpKoinexDown']['data']))
                                delay of {{$result['ETHBittrexUpKoinexDown']['data']['timestamp']}} secs - returns
                                of {{$result['ETHBittrexUpKoinexDown']['data']['returns']}}%
                            @endif
                        </span>
                    </div>
                    <div class="panel-body" style="min-height: 130px; max-height: 135px;">
                        @if(!empty($result['message']))
                            <div>{{ $result['message'] }} </div>
                            <div>&nbsp;</div>
                            <div>&nbsp;</div>

                        @elseif(!empty($result['ETHBittrexUpKoinexDown']['message']))

                            <div>{{ $result['ETHBittrexUpKoinexDown']['message'] }}</div>

                        @elseif(!empty($result['ETHBittrexUpKoinexDown']['isArbitrageOpportunity']))

                            <div class="input-group" style="margin-bottom: 10px">
                                <span class="input-group-addon">1. Buy</span>
                                <input type="text" class="form-control"
                                       value="{{$result['ETHBittrexUpKoinexDown']['data']['INR-ETH']['Quantity']}}"/>
                                <span class="input-group-addon">units of ETH @ Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['ETHBittrexUpKoinexDown']['data']['INR-ETH']['Rate']}}"/>
                                <span class="input-group-addon">per unit to spend a total of Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['ETHBittrexUpKoinexDown']['data']['INR-ETH']['Amount'] }}"/>
                                <span class="input-group-addon">in Koinex</span>
                            </div>

                            <div class="input-group" style="margin-bottom: 10px">
                                <span class="input-group-addon">2. Sell</span>
                                <input type="text" class="form-control"
                                       value="{{$result['ETHBittrexUpKoinexDown']['data']['INR-BTC']['Quantity']}}"/>
                                <span class="input-group-addon">units of BTC @ Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['ETHBittrexUpKoinexDown']['data']['INR-BTC']['Rate']}}"/>
                                <span class="input-group-addon">per unit to get a total of Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['ETHBittrexUpKoinexDown']['data']['INR-BTC']['Amount'] }}"/>
                                <span class="input-group-addon">in Koinex</span>
                            </div>

                            <div class="input-group" style="margin-bottom: 10px; padding-left: 13px">
                                <a href="{{URL::to('/')}}/kpas/koinex/arbitrage/sell/on/bittrex/BTC-ETH/{{ $result['ETHBittrexUpKoinexDown']['data']['BTC-ETH']['Rate'] }} / {{$result['ETHBittrexUpKoinexDown']['data']['BTC-ETH']['Quantity']}}">
                                    3.
                                    Sell {{$result['ETHBittrexUpKoinexDown']['data']['BTC-ETH']['Quantity']}}
                                    units of ETH @
                                    BTC. {{$result['ETHBittrexUpKoinexDown']['data']['BTC-ETH']['Rate']}}
                                    per unit to get a total of
                                    BTC. {{$result['ETHBittrexUpKoinexDown']['data']['BTC-ETH']['Amount'] }}
                                    in Bittrex
                                </a>
                            </div>

                        @else
                            <div>No arbitrage opportunity</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>
                            @if($result['ETHKoinexUpBittrexDown']['isArbitrageOpportunity'] == true)
                                <b>ETHKoinexUpBittrexDownYes</b> -
                            @else
                                <b>ETHKoinexUpBittrexDownNo</b> -
                            @endif
                            @if(!empty($result['ETHKoinexUpBittrexDown']['data']))
                                delay of {{$result['ETHKoinexUpBittrexDown']['data']['timestamp']}} secs - returns
                                of {{$result['ETHKoinexUpBittrexDown']['data']['returns']}}%
                            @endif
                        </span>
                    </div>
                    <div class="panel-body" style="min-height: 130px; max-height: 135px;">
                        @if(!empty($result['message']))
                            <div>{{ $result['message'] }} </div>
                            <div>&nbsp;</div>
                            <div>&nbsp;</div>

                        @elseif(!empty($result['ETHKoinexUpBittrexDown']['message']))

                            <div>{{ $result['ETHKoinexUpBittrexDown']['message'] }}</div>

                        @elseif(!empty($result['ETHKoinexUpBittrexDown']['isArbitrageOpportunity']))

                            <div class="input-group" style="margin-bottom: 10px">
                                <span class="input-group-addon">1. Sell</span>
                                <input type="text" class="form-control"
                                       value="{{$result['ETHKoinexUpBittrexDown']['data']['INR-ETH']['Quantity']}}"/>
                                <span class="input-group-addon">units of ETH @ Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['ETHKoinexUpBittrexDown']['data']['INR-ETH']['Rate']}}"/>
                                <span class="input-group-addon">per unit to get a total of Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['ETHKoinexUpBittrexDown']['data']['INR-ETH']['Amount'] }}"/>
                                <span class="input-group-addon">in Koinex</span>
                            </div>

                            <div class="input-group" style="margin-bottom: 10px">
                                <span class="input-group-addon">2. Buy</span>
                                <input type="text" class="form-control"
                                       value="{{$result['ETHKoinexUpBittrexDown']['data']['INR-BTC']['Quantity']}}"/>
                                <span class="input-group-addon">units of BTC @ Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['ETHKoinexUpBittrexDown']['data']['INR-BTC']['Rate']}}"/>
                                <span class="input-group-addon">per unit to spend a total of Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['ETHKoinexUpBittrexDown']['data']['INR-BTC']['Amount'] }}"/>
                                <span class="input-group-addon">in Koinex</span>
                            </div>

                            <div class="input-group" style="margin-bottom: 10px; padding-left: 13px">
                                <a href="{{URL::to('/')}}/kpas/koinex/arbitrage/sell/on/bittrex/BTC-ETH/{{ $result['ETHKoinexUpBittrexDown']['data']['BTC-ETH']['Rate'] }} / {{$result['ETHKoinexUpBittrexDown']['data']['BTC-ETH']['Quantity']}}">
                                    3.
                                    Buy {{$result['ETHKoinexUpBittrexDown']['data']['BTC-ETH']['Quantity']}}
                                    units of ETH @
                                    BTC. {{$result['ETHKoinexUpBittrexDown']['data']['BTC-ETH']['Rate']}}
                                    per unit to spend a total of
                                    BTC. {{$result['ETHKoinexUpBittrexDown']['data']['BTC-ETH']['Amount'] }}
                                    in Bittrex
                                </a>
                            </div>

                        @else
                            <div>No arbitrage opportunity</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>
                            @if($result['LTCBittrexUpKoinexDown']['isArbitrageOpportunity'] == true)
                                <b>LTCBittrexUpKoinexDownYes</b> -
                            @else
                                <b>LTCBittrexUpKoinexDownNo</b> -
                            @endif
                            @if(!empty($result['LTCBittrexUpKoinexDown']['data']))
                                delay of {{$result['LTCBittrexUpKoinexDown']['data']['timestamp']}} secs - returns
                                of {{$result['LTCBittrexUpKoinexDown']['data']['returns']}}%
                            @endif
                        </span>
                    </div>
                    <div class="panel-body" style="min-height: 130px; max-height: 135px;">
                        @if(!empty($result['message']))
                            <div>{{ $result['message'] }} </div>
                            <div>&nbsp;</div>
                            <div>&nbsp;</div>

                        @elseif(!empty($result['LTCBittrexUpKoinexDown']['message']))

                            <div>{{ $result['LTCBittrexUpKoinexDown']['message'] }}</div>

                        @elseif(!empty($result['LTCBittrexUpKoinexDown']['isArbitrageOpportunity']))

                            <div class="input-group" style="margin-bottom: 10px">
                                <span class="input-group-addon">1. Buy</span>
                                <input type="text" class="form-control"
                                       value="{{$result['LTCBittrexUpKoinexDown']['data']['INR-LTC']['Quantity']}}"/>
                                <span class="input-group-addon">units of LTC @ Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['LTCBittrexUpKoinexDown']['data']['INR-LTC']['Rate']}}"/>
                                <span class="input-group-addon">per unit to spend a total of Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['LTCBittrexUpKoinexDown']['data']['INR-LTC']['Amount'] }}"/>
                                <span class="input-group-addon">in Koinex</span>
                            </div>

                            <div class="input-group" style="margin-bottom: 10px">
                                <span class="input-group-addon">2. Sell</span>
                                <input type="text" class="form-control"
                                       value="{{$result['LTCBittrexUpKoinexDown']['data']['INR-BTC']['Quantity']}}"/>
                                <span class="input-group-addon">units of BTC @ Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['LTCBittrexUpKoinexDown']['data']['INR-BTC']['Rate']}}"/>
                                <span class="input-group-addon">per unit to get a total of Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['LTCBittrexUpKoinexDown']['data']['INR-BTC']['Amount'] }}"/>
                                <span class="input-group-addon">in Koinex</span>
                            </div>

                            <div class="input-group" style="margin-bottom: 10px; padding-left: 13px">
                                <a href="{{URL::to('/')}}/kpas/koinex/arbitrage/sell/on/bittrex/BTC-LTC/{{ $result['LTCBittrexUpKoinexDown']['data']['BTC-LTC']['Rate'] }} / {{$result['LTCBittrexUpKoinexDown']['data']['BTC-LTC']['Quantity']}}">
                                    3.
                                    Sell {{$result['LTCBittrexUpKoinexDown']['data']['BTC-LTC']['Quantity']}}
                                    units of LTC @
                                    BTC. {{$result['LTCBittrexUpKoinexDown']['data']['BTC-LTC']['Rate']}}
                                    per unit to get a total of
                                    BTC. {{$result['LTCBittrexUpKoinexDown']['data']['BTC-LTC']['Amount'] }}
                                    in Bittrex
                                </a>
                            </div>

                        @else
                            <div>No arbitrage opportunity</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>
                            @if($result['LTCKoinexUpBittrexDown']['isArbitrageOpportunity'] == true)
                                <b>LTCKoinexUpBittrexDownYes</b> -
                            @else
                                <b>LTCKoinexUpBittrexDownNo</b> -
                            @endif
                            @if(!empty($result['LTCKoinexUpBittrexDown']['data']))
                                delay of {{$result['LTCKoinexUpBittrexDown']['data']['timestamp']}} secs - returns
                                of {{$result['LTCKoinexUpBittrexDown']['data']['returns']}}%
                            @endif
                        </span>
                    </div>
                    <div class="panel-body" style="min-height: 130px; max-height: 135px;">
                        @if(!empty($result['message']))
                            <div>{{ $result['message'] }} </div>
                            <div>&nbsp;</div>
                            <div>&nbsp;</div>

                        @elseif(!empty($result['LTCKoinexUpBittrexDown']['message']))

                            <div>{{ $result['LTCKoinexUpBittrexDown']['message'] }}</div>

                        @elseif(!empty($result['LTCKoinexUpBittrexDown']['isArbitrageOpportunity']))

                            <div class="input-group" style="margin-bottom: 10px">
                                <span class="input-group-addon">1. Sell</span>
                                <input type="text" class="form-control"
                                       value="{{$result['LTCKoinexUpBittrexDown']['data']['INR-LTC']['Quantity']}}"/>
                                <span class="input-group-addon">units of LTC @ Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['LTCKoinexUpBittrexDown']['data']['INR-LTC']['Rate']}}"/>
                                <span class="input-group-addon">per unit to get a total of Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['LTCKoinexUpBittrexDown']['data']['INR-LTC']['Amount'] }}"/>
                                <span class="input-group-addon">in Koinex</span>
                            </div>

                            <div class="input-group" style="margin-bottom: 10px">
                                <span class="input-group-addon">2. Buy</span>
                                <input type="text" class="form-control"
                                       value="{{$result['LTCKoinexUpBittrexDown']['data']['INR-BTC']['Quantity']}}"/>
                                <span class="input-group-addon">units of BTC @ Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['LTCKoinexUpBittrexDown']['data']['INR-BTC']['Rate']}}"/>
                                <span class="input-group-addon">per unit to spend a total of Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['LTCKoinexUpBittrexDown']['data']['INR-BTC']['Amount'] }}"/>
                                <span class="input-group-addon">in Koinex</span>
                            </div>

                            <div class="input-group" style="margin-bottom: 10px; padding-left: 13px">
                                <a href="{{URL::to('/')}}/kpas/koinex/arbitrage/sell/on/bittrex/BTC-LTC/{{ $result['LTCKoinexUpBittrexDown']['data']['BTC-LTC']['Rate'] }} / {{$result['LTCKoinexUpBittrexDown']['data']['BTC-LTC']['Quantity']}}">
                                    3.
                                    Buy {{$result['LTCKoinexUpBittrexDown']['data']['BTC-LTC']['Quantity']}}
                                    units of LTC @
                                    BTC. {{$result['LTCKoinexUpBittrexDown']['data']['BTC-LTC']['Rate']}}
                                    per unit to spend a total of
                                    BTC. {{$result['LTCKoinexUpBittrexDown']['data']['BTC-LTC']['Amount'] }}
                                    in Bittrex
                                </a>
                            </div>

                        @else
                            <div>No arbitrage opportunity</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>
                            @if($result['XRPBittrexUpKoinexDown']['isArbitrageOpportunity'] == true)
                                <b>XRPBittrexUpKoinexDownYes</b> -
                            @else
                                <b>XRPBittrexUpKoinexDownNo</b> -
                            @endif
                            @if(!empty($result['XRPBittrexUpKoinexDown']['data']))
                                delay of {{$result['XRPBittrexUpKoinexDown']['data']['timestamp']}} secs - returns
                                of {{$result['XRPBittrexUpKoinexDown']['data']['returns']}}%
                            @endif
                        </span>
                    </div>
                    <div class="panel-body" style="min-height: 130px; max-height: 135px;">
                        @if(!empty($result['message']))
                            <div>{{ $result['message'] }} </div>
                            <div>&nbsp;</div>
                            <div>&nbsp;</div>

                        @elseif(!empty($result['XRPBittrexUpKoinexDown']['message']))

                            <div>{{ $result['XRPBittrexUpKoinexDown']['message'] }}</div>

                        @elseif(!empty($result['XRPBittrexUpKoinexDown']['isArbitrageOpportunity']))

                            <div class="input-group" style="margin-bottom: 10px">
                                <span class="input-group-addon">1. Buy</span>
                                <input type="text" class="form-control"
                                       value="{{$result['XRPBittrexUpKoinexDown']['data']['INR-XRP']['Quantity']}}"/>
                                <span class="input-group-addon">units of XRP @ Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['XRPBittrexUpKoinexDown']['data']['INR-XRP']['Rate']}}"/>
                                <span class="input-group-addon">per unit to spend a total of Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['XRPBittrexUpKoinexDown']['data']['INR-XRP']['Amount'] }}"/>
                                <span class="input-group-addon">in Koinex</span>
                            </div>

                            <div class="input-group" style="margin-bottom: 10px">
                                <span class="input-group-addon">2. Sell</span>
                                <input type="text" class="form-control"
                                       value="{{$result['XRPBittrexUpKoinexDown']['data']['INR-BTC']['Quantity']}}"/>
                                <span class="input-group-addon">units of BTC @ Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['XRPBittrexUpKoinexDown']['data']['INR-BTC']['Rate']}}"/>
                                <span class="input-group-addon">per unit to get a total of Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['XRPBittrexUpKoinexDown']['data']['INR-BTC']['Amount'] }}"/>
                                <span class="input-group-addon">in Koinex</span>
                            </div>

                            <div class="input-group" style="margin-bottom: 10px; padding-left: 13px">
                                <a href="{{URL::to('/')}}/kpas/koinex/arbitrage/sell/on/bittrex/BTC-XRP/{{ $result['XRPBittrexUpKoinexDown']['data']['BTC-XRP']['Rate'] }} / {{$result['XRPBittrexUpKoinexDown']['data']['BTC-XRP']['Quantity']}}">
                                    3.
                                    Sell {{$result['XRPBittrexUpKoinexDown']['data']['BTC-XRP']['Quantity']}}
                                    units of XRP @
                                    BTC. {{$result['XRPBittrexUpKoinexDown']['data']['BTC-XRP']['Rate']}}
                                    per unit to get a total of
                                    BTC. {{$result['XRPBittrexUpKoinexDown']['data']['BTC-XRP']['Amount'] }}
                                    in Bittrex
                                </a>
                            </div>

                        @else
                            <div>No arbitrage opportunity</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>
                            @if($result['XRPKoinexUpBittrexDown']['isArbitrageOpportunity'] == true)
                                <b>XRPKoinexUpBittrexDownYes</b> -
                            @else
                                <b>XRPKoinexUpBittrexDownNo</b> -
                            @endif
                            @if(!empty($result['XRPKoinexUpBittrexDown']['data']))
                                delay of {{$result['XRPKoinexUpBittrexDown']['data']['timestamp']}} secs - returns
                                of {{$result['XRPKoinexUpBittrexDown']['data']['returns']}}%
                            @endif
                        </span>
                    </div>
                    <div class="panel-body" style="min-height: 130px; max-height: 135px;">
                        @if(!empty($result['message']))
                            <div>{{ $result['message'] }} </div>
                            <div>&nbsp;</div>
                            <div>&nbsp;</div>

                        @elseif(!empty($result['XRPKoinexUpBittrexDown']['message']))

                            <div>{{ $result['XRPKoinexUpBittrexDown']['message'] }}</div>

                        @elseif(!empty($result['XRPKoinexUpBittrexDown']['isArbitrageOpportunity']))

                            <div class="input-group" style="margin-bottom: 10px">
                                <span class="input-group-addon">1. Sell</span>
                                <input type="text" class="form-control"
                                       value="{{$result['XRPKoinexUpBittrexDown']['data']['INR-XRP']['Quantity']}}"/>
                                <span class="input-group-addon">units of XRP @ Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['XRPKoinexUpBittrexDown']['data']['INR-XRP']['Rate']}}"/>
                                <span class="input-group-addon">per unit to get a total of Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['XRPKoinexUpBittrexDown']['data']['INR-XRP']['Amount'] }}"/>
                                <span class="input-group-addon">in Koinex</span>
                            </div>

                            <div class="input-group" style="margin-bottom: 10px">
                                <span class="input-group-addon">2. Buy</span>
                                <input type="text" class="form-control"
                                       value="{{$result['XRPKoinexUpBittrexDown']['data']['INR-BTC']['Quantity']}}"/>
                                <span class="input-group-addon">units of BTC @ Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['XRPKoinexUpBittrexDown']['data']['INR-BTC']['Rate']}}"/>
                                <span class="input-group-addon">per unit to spend a total of Rs.</span>
                                <input type="text" class="form-control"
                                       value="{{$result['XRPKoinexUpBittrexDown']['data']['INR-BTC']['Amount'] }}"/>
                                <span class="input-group-addon">in Koinex</span>
                            </div>

                            <div class="input-group" style="margin-bottom: 10px; padding-left: 13px">
                                <a href="{{URL::to('/')}}/kpas/koinex/arbitrage/sell/on/bittrex/BTC-XRP/{{ $result['XRPKoinexUpBittrexDown']['data']['BTC-XRP']['Rate'] }} / {{$result['XRPKoinexUpBittrexDown']['data']['BTC-XRP']['Quantity']}}">
                                    3.
                                    Buy {{$result['XRPKoinexUpBittrexDown']['data']['BTC-XRP']['Quantity']}}
                                    units of XRP @
                                    BTC. {{$result['XRPKoinexUpBittrexDown']['data']['BTC-XRP']['Rate']}}
                                    per unit to spend a total of
                                    BTC. {{$result['XRPKoinexUpBittrexDown']['data']['BTC-XRP']['Amount'] }}
                                    in Bittrex
                                </a>
                            </div>

                        @else
                            <div>No arbitrage opportunity</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-4">
                <div class="input-group" style="margin-bottom: 10px">
                    <span class="input-group-addon">Bittrex buy/sell order status indicator</span>
                    @if(!empty($result['bittrexOrderResponseIndicator']))
                        @if($result['bittrexOrderResponseIndicator'] == true)
                            <input type="text" class="form-control" value="Passed"/>
                        @else
                            <input type="text" class="form-control" value="Failed"/>
                        @endif
                    @else
                        <input type="text" class="form-control" value="None"/>
                    @endif
                </div>
            </div>
            <div class="col-lg-4">
                <div style="margin-bottom: 10px">
                    <a href="{{URL::to('/')}}/bittrex/koinex/arbitrage/fake/koinex/markets/volume">Fake Koinex markets'
                        volume</a>
                </div>
            </div>
            <div class="col-lg-4">
                <div style="margin-bottom: 10px">
                    <a href="{{URL::to('/')}}/bittrex/koinex/arbitrage/json/data" target="_blank">View Json data</a>
                </div>
            </div>
        </div>

    </div>
@stop
