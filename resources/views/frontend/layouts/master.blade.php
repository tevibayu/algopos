<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" type="image/ico" href='{{ asset("favicon.png") }}' />
    <title>@yield('title', app_name())</title>
    <meta name="description" content="@yield('meta_description', 'Default Description')">
    @yield('meta')

    @yield('before-styles-end')
    {!! HTML::style('public/css/frontend.css') !!}
    @yield('after-styles-end')

    <!-- Fonts -->
    {!! HTML::style('public/css/style.css') !!}
    <!-- {!! HTML::style('public/fonts/java-valley/javavalley.css') !!} -->
    {!! HTML::style('public/plugin/font-awesome/css/font-awesome.css') !!}
    {!! HTML::style('public/css/custom/skin-1.css') !!}
    {!! HTML::style('public/css/custom/signin.css') !!}
    <script src="{{asset('public/js/vendor/jquery-1.11.2.min.js')}}"></script>
    {!! HTML::script('public/js/ajax-setup.js'.'?v='.env("APP_VERSION", "1.0.0")) !!}
    <!-- Icons-->
    <link rel="apple-touch-icon" href="public/apple-touch-icon.png">
    <!-- Place favicon.ico in the root directory -->
    <!--<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto:300,400,700,900|Source+Sans+Pro:300,400,700" rel="stylesheet">-->
    {!! HTML::style('public/fonts/custom/fonts.css') !!}
    {!! HTML::script("public/js/vendor/modernizr-2.8.3.min.js") !!}
</head>
<body class="skin-default skin-blue" id="javavalley">
    
    <div class="wrapper">
        <!--@include('includes.partials.messages')-->
        @yield('content')
    </div>
    @include('backend.includes.footer')


    {!! HTML::script('public/js/vendor/jquery-1.11.2.min.js') !!}
    <script>window.jQuery || document.write('<script src="{{asset('public/js/vendor/jquery-1.11.2.min.js')}}"><\/script>')</script>
    {!! HTML::script('public/js/vendor/bootstrap.min.js') !!}

    @yield('before-scripts-end')
    {!! HTML::script('public/js/frontend.js') !!}
    @yield('after-scripts-end')
    {!! HTML::style('public/css/ajax-loader.css'.'?v='.env("APP_VERSION", "1.0.0")) !!}
    <img class="ajax_loader" src="{{ asset('public/img/ajax-loader.GIF') }}">
</body>
</html>
