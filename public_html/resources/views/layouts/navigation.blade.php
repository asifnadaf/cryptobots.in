<?php $authenticationHelper = \App::make('authentication_helper');
$permissions = array('_superadmin');
?>

<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{URL::to('/')}}">
            {{ HTML::image('images/cryptobots.png', 'CryptoBotPro Logo', array('class' => 'landingpage')) }}
        </a>
    </div>
    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">

            @if($authenticationHelper->hasPermission(array('_superadmin')))
                <ul class="nav" id="side-menu">
                    <li><a href="{{URL::to('/')}}/clients">&raquo; Trading accounts </a></li>
                    <li><a href="{{URL::to('/')}}/odds">&raquo; Market Odds</a></li>
                    <li><a href="{{URL::to('/')}}/opportunities">&raquo; Past investment opportunities </a></li>
                    <li><a href="{{URL::to('/')}}/btc/index">&raquo; Bittrex BTC Index </a></li>
                    <li><a href="{{URL::to('/')}}/past/market/statistics">&raquo; Past Market Statistics </a></li>
                    <li><a href="{{URL::to('/')}}/market/pumps">&raquo; Market Pumps </a></li>
                    <li><a href="{{URL::to('/')}}/market/dumps">&raquo; Market Dumps </a></li>
                    <li><a href="{{URL::to('/')}}/market/listing">&raquo; Market Listing </a></li>
                    <li><a href="{{URL::to('/')}}/market/delisting">&raquo; Market Delisting </a></li>
                    <li>
                        <a href="#">&raquo; Bittrex Koinex Arbitrage<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="{{URL::to('/')}}/bittrex/koinex/arbitrage/overview">&raquo; Arbitrage Overview </a></li>
                            <li><a href="{{URL::to('/')}}/bittrex/koinex/arbitrage/koinex/orderbook">&raquo; Koinex order book </a></li>
                            <li><a href="{{URL::to('/')}}/bittrex/koinex/arbitrage/opportunities">&raquo; Arbitrage opportunities </a></li>
                            {{--<li><a href="{{URL::to('/')}}/bcc/bittrex/up/koinex/down">&raquo; BCC - Bittrex <span class="fa fa-arrow-up"></span> Koinex <span class="fa fa-arrow-down"></span> </a></li>--}}
                            {{--<li><a href="{{URL::to('/')}}/bcc/koinex/up/bittrex/down">&raquo; BCC - Koinex <span class="fa fa-arrow-up"></span> Bittrex <span class="fa fa-arrow-down"></span> </a></li>--}}
                            {{--<li><a href="{{URL::to('/')}}/eth/bittrex/up/koinex/down">&raquo; ETH - Bittrex <span class="fa fa-arrow-up"></span> Koinex <span class="fa fa-arrow-down"></span> </a></li>--}}
                            {{--<li><a href="{{URL::to('/')}}/eth/koinex/up/bittrex/down">&raquo; ETH - Koinex <span class="fa fa-arrow-up"></span> Bittrex <span class="fa fa-arrow-down"></span> </a></li>--}}
                            {{--<li><a href="{{URL::to('/')}}/ltc/bittrex/up/koinex/down">&raquo; LTC - Bittrex <span class="fa fa-arrow-up"></span> Koinex <span class="fa fa-arrow-down"></span> </a></li>--}}
                            {{--<li><a href="{{URL::to('/')}}/ltc/koinex/up/bittrex/down">&raquo; LTC - Koinex <span class="fa fa-arrow-up"></span> Bittrex <span class="fa fa-arrow-down"></span> </a></li>--}}
                            {{--<li><a href="{{URL::to('/')}}/xrp/bittrex/up/koinex/down">&raquo; XRP - Bittrex <span class="fa fa-arrow-up"></span> Koinex <span class="fa fa-arrow-down"></span> </a></li>--}}
                            {{--<li><a href="{{URL::to('/')}}/xrp/koinex/up/bittrex/down">&raquo; XRP - Koinex <span class="fa fa-arrow-up"></span> Bittrex <span class="fa fa-arrow-down"></span> </a></li>--}}
                            <li><a href="{{URL::to('/')}}/kpas/crypto/balance">&raquo; Account balance </a></li>
                            <li><a href="{{URL::to('/')}}/kbas">&raquo; Arbitrage settings </a></li>
                        </ul>
                    </li>
                    <li><a href="{{URL::to('/')}}/arbitrage/opportunities">&raquo; International Arbitrage </a></li>
                    <li><a href="{{URL::to('/')}}/bittrex/indian/exchanges/arbitrage/opportunities">&raquo; Indian Exchanges Arbitrage </a></li>

                    {{--<li><a href="{{URL::to('/')}}/reverse/arbitrage/opportunities">&raquo; Reverse arbitrage--}}
                            {{--opportunities </a></li>--}}
                    <li><a href="{{URL::to('/')}}/altcoininfo">&raquo; Altcoin Information </a></li>
                    <li><a href="{{URL::to('/')}}/bot/status">&raquo; Bots Status </a></li>
                    <li>
                        <a href="#">&raquo; Bots Setting<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="{{URL::to('/')}}/buysetting">&raquo; Buy settings </a></li>
                            <li><a href="{{URL::to('/')}}/sellsetting">&raquo; Sell settings </a></li>
                            <li><a href="{{URL::to('/')}}/startpausebotsetting">&raquo; Start / Pause Bots </a></li>
                            <li><a href="{{URL::to('/')}}/mailinglistsetting">&raquo; Mailing list </a></li>
                        </ul>
                    </li>
                    <li><a href="{{URL::to('/')}}/user/logout">&raquo; Logout</a></li>
                </ul>
            @endif


            @if($authenticationHelper->hasPermission(array('_agent')))
                <ul class="nav" id="side-menu">
                    <li><a href="{{URL::to('/')}}/clients">&raquo; Trading accounts </a></li>
                    <li><a href="{{URL::to('/')}}/market/pumps">&raquo; Market Pumps </a></li>
                    <li><a href="{{URL::to('/')}}/market/dumps">&raquo; Market Dumps </a></li>
                    <li><a href="{{URL::to('/')}}/market/listing">&raquo; Market Listing </a></li>
                    <li><a href="{{URL::to('/')}}/market/delisting">&raquo; Market Delisting </a></li>
                    <li><a href="{{URL::to('/')}}/user/logout">&raquo; Logout</a></li>
                </ul>
            @endif

        </div>
    </div>

</nav>