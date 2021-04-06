<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{{ csrf_token() }}" />
        <link rel="shortcut icon" type="image/ico" href='{{ asset("favicon.png") }}' />
        {!! HTML::style('public/fonts/ubuntu/ubuntu.css') !!}
        {!! Charts::assets() !!}
        
        <title>
            {{ app_name() }} 
            <?php
                if (isset($__env->getSections()['title'])) {
                    echo '::';
                }
            ?>
            @yield('title')
        </title>
        <meta name="description" content="@yield('meta_description', 'Default Description')">
        @yield('meta')

        <style>
            * {
             font-size: 100%;
             font-family: Ubuntu !important;
             line-height: 21px;
            }

            .main-header .sidebar-toggle {
                font-family: fontAwesome !important;
            }
        </style>

        @yield('before-styles-end')
        {!! HTML::style('public/css/backend.css') !!}
        {!! HTML::style('public/css/backend/plugin/datatable/bootstrap-dataTables.css') !!}
        @yield('after-styles-end')
        
        {!! HTML::style('public/plugin/font-awesome/css/font-awesome.css') !!}
        {!! HTML::style('public/ion/ionicons.min.css') !!}
        
        {!! HTML::script('public/js/vendor/jquery-1.11.2.min.js') !!}
        {!! HTML::script('public/js/ajax-setup.js') !!}

        
        
        <script>
            var siteurl = '{{ url() }}';
            var my_token = '{{ csrf_token() }}';
        </script>

       
    </head>
    <body class="skin-black">
        <div class="wrapper">
          @include('backend.includes.header')
          @include('backend.includes.sidebar')

          <!-- Content Wrapper. Contains page content -->
          <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
              @yield('page-header')
              <ol class="breadcrumb">
                @yield('breadcrumbs')
              </ol>
            </section>

            <!-- Main content -->
            <section class="content">
              @include('includes.partials.messages')
              @yield('content')
            </section><!-- /.content -->
          </div><!-- /.content-wrapper -->

          @include('backend.includes.footer')
        </div><!-- ./wrapper -->

        <script>window.jQuery || document.write('<script src="{{asset('public/js/vendor/jquery-1.11.2.min.js')}}"><\/script>')</script>
        {!! HTML::script('public/js/vendor/bootstrap.min.js') !!}

        @yield('before-scripts-end')
        {!! HTML::script('public/js/backend.js') !!}
        {!! HTML::script('public/js/backend/plugin/datatable/jquery.dataTables.min.js') !!}
        {!! HTML::script('public/js/backend/plugin/datatable/datatable_option.js') !!}
        @yield('after-scripts-end')
        
        {!! HTML::style('public/css/ajax-loader.css') !!}
        <img class="ajax_loader" src="{{ asset('public/img/ajax-loader.GIF') }}">
        
    </body>
</html>
