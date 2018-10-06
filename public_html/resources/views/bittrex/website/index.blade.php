@extends('layouts.landingpage.index')

@section('content')
    <div class="main_header type1">
        <div class="tagline">
            <div class="container">
                <div class="fleft">
                    <div class="phone"><i class="icon-phone"></i><span style="font-weight: bold"> +91 9819148511</span>
                    </div>
                    <div class="email"><a href="mailto:info@cryptobots.in"><i class="icon-envelope"></i><span
                                    style="font-weight: bold">
                            info@cryptobots.in</span></a>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <div class="wrapper">
        <div class="container">
            <div class="content_block row no-sidebar">
                <div class="fl-container">
                    <div class="posts-block">
                        <div class="contentarea"> <!-- Slider -->
                            {{--<div class="fw_block bg_start wall_wrap">--}}
                            {{--<div class="row">--}}
                            {{--<div class="col-sm-12 first-module module_slider module_cont pb0">--}}
                            {{--<div class="slider_container">--}}
                            {{--<div class="fullscreen_slider slider_bg">--}}
                            {{--<ul> <!-- SLIDE 1 -->--}}
                            {{--<li data-transition="fade" data-slotamount="5"--}}
                            {{--data-masterspeed="700">--}}
                            {{--<img src="{{ URL::to('images/landingpage/slider/transparent.png') }}"--}}
                            {{--alt="slide3"--}}
                            {{--data-bgposition="center top" data-bgfit="cover"--}}
                            {{--data-bgrepeat="no-repeat"/> <!-- LAYER NR. 1 -->--}}
                            {{--<!-- LAYER NR. 2 --> <!-- LAYER NR. 3 --> <!-- LAYER NR. 4 -->--}}
                            {{--<div class="tp-caption lft ltt tp-resizeme slide_title_home"--}}
                            {{--data-x="0" data-y="150" data-speed="1000" data-start="1800"--}}
                            {{--data-easing="Power4.easeOut" data-endspeed="300"--}}
                            {{--data-endeasing="Power4.easeIn">{{ HTML::image('images/landingpage/cryptowatch.png', 'CryptoWatch') }}--}}
                            {{--</div>--}}
                            {{--<div class="tp-caption lft ltt tp-resizeme slide_title_home font_size40"--}}
                            {{--data-x="0" data-y="250" data-speed="1000" data-start="2500"--}}
                            {{--data-easing="Power4.easeOut" data-endspeed="600"--}}
                            {{--data-endeasing="Power4.easeIn">Bitcoins Trading Bot--}}
                            {{--</div>--}}
                            {{--<div class="tp-caption lft ltt tp-resizeme slide_title_home font_size40"--}}
                            {{--data-x="0" data-y="300" data-speed="1000" data-start="2500"--}}
                            {{--data-easing="Power4.easeOut" data-endspeed="600"--}}
                            {{--data-endeasing="Power4.easeIn">powered by--}}
                            {{--Artificial Intelligence--}}
                            {{--</div>--}}
                            {{--<img src={{ URL::to('images/landingpage/slider/transparent.png') }}--}}
                            {{--alt="slide3"--}}
                            {{--data-bgposition="center top" data-bgfit="cover"--}}
                            {{--data-bgrepeat="no-repeat"/> <!-- LAYER NR. 1 -->--}}
                            {{--<div class="tp-caption customin z_index2" data-x="692"--}}
                            {{--data-y="0"--}}
                            {{--data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:0;skewX:0;skewY:0;opacity:0;transformPerspective:1000;transformOrigin:50% 50%;"--}}
                            {{--data-speed="1500" data-start="500"--}}
                            {{--data-easing="Power3.easeInOut" data-endspeed="300"><img--}}
                            {{--src="{{ URL::to('images/landingpage/cryptowatch-software.png') }}"--}}
                            {{--alt="CryptoWatch Bot"/></div>--}}

                            {{--</li>--}}
                            {{--</ul>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}

                            <div class="row pb210">
                                <div class="app_page_title_block">
                                    <div class="page_descr animate" data-anim-delay="900" data-anim-type="fadeInUp"><img
                                                alt="limited time offer"
                                                src="{{ URL::to('images/cryptobots.png') }}">
                                    </div>
                                    <div class="page_descr animate" data-anim-delay="1500" data-anim-type="fadeInUp"><h2>
                                            Bitcoins Trading Bot powered by Artificial
                                            Intelligence</h2>
                                    </div>

                                </div>
                            </div>


                            <div class="row pt210 pb210">
                                <div class="app_page_title_block">
                                    <div class="app_bg_title"><h2>Limited time Investment opportunity</h2></div>
                                    <div class="page_descr"><p style="text-align: center;">This is close ended scheme.
                                            We are going to close this opportunity once we receive required BTC
                                            investment. So, Hurry up!!!</p>
                                    </div>
                                    <div class="page_descr animate" data-anim-delay="300" data-anim-type="fadeInUp"><img
                                                alt="limited time offer"
                                                src="{{ URL::to('images/landingpage/limitedtimeoffer.png') }}" style="max-width: 30vh">
                                    </div>
                                </div>
                            </div>


                            <div class="row pt140 pb140" align="center">

                                <div class="pb85">
                                    <div class="app_page_title_block">
                                        <div class="app_bg_title"><h2>Key Features</h2></div>
                                    </div>
                                </div>

                                <div class="col-sm-6 module_cont animate" data-anim-delay="300"
                                     data-anim-type="fadeInLeft"><img
                                            alt="Your BTCs are Safe"
                                            src="{{ URL::to('images/landingpage/bitcoin.png') }}" style="max-width: 30vh">
                                </div>
                                <div class="col-sm-6 module_cont animate" data-anim-delay="300"
                                     data-anim-type="fadeInRight">
                                    <h2 class="mb20" align="left">Your BTCs are Safe</h2>
                                    <p class="mb28" align="left">No need to transfer BTC into our account. You keep the
                                        rights to both deposit and withdraw your BTC. The bot has rights to perform
                                        trading only.</p>
                                </div>
                            </div>

                            <div class="row pt140 pb140">
                                <div class="col-sm-6 module_cont animate " data-anim-delay="300"
                                     data-anim-type="fadeInLeft" align="center"><img
                                            alt="On the Cloud"
                                            src="{{ URL::to('images/landingpage/cloud.png') }}" style="max-width: 30vh">
                                </div>
                                <div class="col-sm-6 module_cont animate" data-anim-delay="300"
                                     data-anim-type="fadeInRight"><h3 class="mb20">On the Cloud</h3>
                                    <p class="mb28">The trading bot is hosted on the cloud. You have zero
                                        setup
                                        cost.</p>
                                </div>
                            </div>

                            <div class="row pt140 pb140" align="center">
                                <div class="col-sm-6 module_cont animate" data-anim-delay="300"
                                     data-anim-type="fadeInLeft"><img
                                            alt="24/7 Trading"
                                            src="{{ URL::to('images/landingpage/24by7.png') }}" style="max-width: 30vh">

                                </div>
                                <div class="col-sm-6 module_cont animate" data-anim-delay="300"
                                     data-anim-type="fadeInRight">
                                    <h2 class="mb20" align="left">24/7 Trading</h2>
                                    <p class="mb28" align="left">The bot works 24/7 and it performs trades whenever
                                        opportunities for profits arise.</p>
                                </div>
                            </div>
                            <div class="row pt140 pb140">

                                <div class="col-sm-6 module_cont animate " data-anim-delay="300"
                                     data-anim-type="fadeInLeft" align="center"><img
                                            alt="Optimized for Bittrex exchange"
                                            src="{{ URL::to('images/landingpage/bittrex.png') }}" style="max-width: 30vh">
                                </div>
                                <div class="col-sm-6 module_cont animate" data-anim-delay="300"
                                     data-anim-type="fadeInRight"><h3 class="mb20">Optimized for Bittrex exchange</h3>
                                    <p class="mb28">We have optimized the artificial intelligence of this bot to trade
                                        on
                                        Bittrex exchange on selected altcoins.</p>
                                </div>

                            </div>


                            <div class="row pt140 pb140" align="center">
                                <div class="col-sm-6 module_cont animate" data-anim-delay="300"
                                     data-anim-type="fadeInLeft"><img
                                            alt="Setup within minutes"
                                            src="{{ URL::to('images/landingpage/timer.png') }}" style="max-width: 30vh">

                                </div>
                                <div class="col-sm-6 module_cont animate" data-anim-delay="300"
                                     data-anim-type="fadeInRight">
                                    <h2 class="mb20" align="left">Setup within minutes</h2>
                                    <p class="mb28" align="left">You have to share api key and secret key for trading
                                        from
                                        your Bittrex account. That's it!!!</p>
                                </div>
                            </div>
                            <div class="row pt140 pb140">
                                <div class="col-sm-6 module_cont animate " data-anim-delay="300"
                                     data-anim-type="fadeInLeft" align="center"><img
                                            alt="No upfront Cost or Fee"
                                            src="{{ URL::to('images/landingpage/zero-upfront-free.png') }}" style="max-width: 30vh">

                                </div>
                                <div class="col-sm-6 module_cont animate" data-anim-delay="300"
                                     data-anim-type="fadeInRight"><h3 class="mb20">No upfront Cost or Fee</h3>
                                    <p class="mb28">We make money only when you make money. You keep 50% of the profit
                                        and
                                        we keep rest of the profit.</p>
                                </div>
                            </div>


                            <div class="row pt140 pb140" align="center">

                                <div class="pb15">
                                    <div class="app_page_title_block">
                                        <div class="app_bg_title"><h2>Clients' Feedback</h2></div>
                                    </div>
                                </div>

                                <div class="module_cont animate" data-anim-delay="300"
                                     data-anim-type="fadeInRight">
                                    <blockquote class="blockquote">
                                        <p class="mb-0 text-left  pb40">The entire process took just 5 minutes. Now, I
                                            am
                                            earning 15-20% monthly returns on idle BTCs without any efforts. --- Kamal
                                            Singh</p>
                                    </blockquote>
                                    <blockquote class="blockquote">
                                        <p class="mb-0 text-left  pb40">I came across this bot and immediately signed up
                                            for
                                            it. The entire process was smooth. The icing on the cake was aprox 15-18%
                                            returns from the first month itself. --- Ajeet Jain</p>
                                    </blockquote>
                                    <blockquote class="blockquote ">
                                        <p class="mb-0 text-left pb40">Simple awesome bot. No need to handover your
                                            BTCs.
                                            Your BTCs are under your control. Five star from my end.. --- Subhash
                                            Apte</p>
                                    </blockquote>
                                </div>
                            </div>

                            <!-- HIRE US -->
                            <div class="row">
                                <div class="col-sm-12 module_cont animate" data-anim-delay="250"
                                     data-anim-type="fadeInUp">
                                    <div class="shortcode_promoblock">
                                        <div class="promoblock_wrapper">
                                            <div class="promo_text_block">
                                                <div class="promo_text_block_wrapper"><h2 class="promo_text_main_title">
                                                        What are you waiting for!</h2>

                                                    <p class="promo_text_additional_title">Please contact us to get
                                                        access to live demo</p>
                                                    <p class="promo_text_additional_title"><span>Phone:</span> +91
                                                        9819148511</p>
                                                    <p class="promo_text_additional_title"><span>Email:</span> <a
                                                                href="mailto:#">info@cryptobots.in</a></p>
                                                    <p class="promo_text_additional_title"><span>Address:</span>
                                                        CryptoWatch, C-35,
                                                        shanti shopping center, Mira road east, Thane
                                                        -
                                                        401107 </p>

                                                </div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="container">
            <div class="footer_bottom">
                <div class="copyright"><span style="font-weight: bold">Copyright 2017. All Rights Reserved by CryptoWatch</span>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>

@stop
