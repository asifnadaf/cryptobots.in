{{--{{ HTML::script('landingpage' ) }}--}}
{{ HTML::script('css/ad/bower_components/jquery/dist/jquery.min.js') }}
{{ HTML::script('js/landingpage/jquery.isotope.min.js' ) }}
{{ HTML::script('js/landingpage/jquery.themepunch.plugins.min.js' ) }}
{{ HTML::script('js/landingpage/jquery.themepunch.revolution.min.js' ) }}
{{ HTML::script('js/landingpage/bootstrap.min.js' ) }}
{{ HTML::script('js/landingpage/modules.js' ) }}
{{ HTML::script('js/landingpage/theme.js' ) }}
<script type="text/javascript" src='js/landingpage/app.js'></script>
<script type="text/javascript"> jQuery(document).ready(function () {
        "use strict";
        jQuery('.fullscreen_slider').show().revolution({
            delay: 5000,
            startwidth: 1170,
            startheight: 765,
            fullWidth: "off",
            fullScreen: "on",
            navigationType: "bullet",
            fullScreenOffsetContainer: ".main_header",
            fullScreenOffset: ""
        });
    }); </script>
{{ HTML::script('js/landingpage/sorting.js' ) }}
<script> items_set = [{
        src: 'landingpage/portfolio/370_300/10.jpg',
        zoom: 'landingpage/portfolio/370_300/10.jpg',
        url: 'portfolio_post_fullwidth.html',
        columnclass: 'col-sm-4',
        sortcategory: 'webui',
        title: 'Unde Sed ut',
        itemcategory: 'Print Design'
    }, {
        src: 'landingpage/portfolio/370_300/11.jpg',
        zoom: 'landingpage/portfolio/370_300/11.jpg',
        url: 'portfolio_post_fullwidth.html',
        columnclass: 'col-sm-4',
        sortcategory: 'polygraphy',
        title: 'Tempore Nam Libero',
        itemcategory: 'Business'
    }, {
        src: 'landingpage/portfolio/370_300/12.jpg',
        zoom: 'landingpage/portfolio/370_300/12.jpg',
        url: 'portfolio_post_fullwidth.html',
        columnclass: 'col-sm-4',
        sortcategory: 'textstyle',
        title: 'Dolores Magni',
        itemcategory: 'People'
    }];
</script>
