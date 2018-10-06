<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{URL::to('/')}}/clients">
            {{ HTML::image('images/cryptobots.png', 'CryptoWatch Logo', array('class' => 'landingpage')) }}
        </a>
    </div>
    <!-- /.navbar-header -->

    <!-- /.navbar-top-links -->

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                {{--<li><a href="{{URL::to('/')}}/clients">&raquo; Trading Accounts </a></li>--}}
                {{--<li><a href="{{URL::to('/')}}/altcoins/support/price">&raquo; Altcoins Support Price </a></li>--}}
                {{--<li><a href="{{URL::to('/')}}/market/pumps">&raquo; Market Pumps </a></li>--}}
                {{--<li><a href="{{URL::to('/')}}/market/dumps">&raquo; Market Dumps </a></li>--}}
                {{--<li><a href="{{URL::to('/')}}/investment/index">&raquo; Buy Recommendation list </a></li>--}}
                {{--<li><a href="{{URL::to('/')}}/whitelist">&raquo; Whitelist </a></li>--}}
                {{--<li><a href="{{URL::to('/')}}/blacklisted/price">&raquo; Blacklisted - General </a></li>--}}
                {{--<li><a href="{{URL::to('/')}}/twentypercentup/index">&raquo; Blacklisted - Today Atcoin is Up </a></li>--}}
                {{--<li><a href="{{URL::to('/')}}/blacklisted/lowvolume">&raquo; Blacklisted - Low Volume </a></li>--}}
                {{--<li><a href="{{URL::to('/')}}/altcoin/information">&raquo; Altcoin information </a></li>--}}
                {{--<li><a href="{{URL::to('/')}}/settings/list">&raquo; ArbitrageSettings</a></li>--}}
                {{--<li><a href="{{URL::to('/')}}/changePassword">&raquo; Change Password</a></li>--}}
                <li><a href="{{URL::to('/')}}/logout">&raquo; Logout</a></li>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>