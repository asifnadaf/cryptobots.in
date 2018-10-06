<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf_token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" >

    <title>CryptoBots</title>

@include('layouts.landingpage.styles')

</head>

<body>


<div id="wrapper">
    <div class="overlay"></div>

@yield('content')

</div>

</body>

@include('layouts.landingpage.scripts')

</html>
