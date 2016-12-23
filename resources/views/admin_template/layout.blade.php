<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>TagTaste</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
        <link rel="stylesheet" href="{{ elixir('css/admin.css') }}">
    </head>
    <body class="fixed-sidebar no-skin-config">
        <script type="text/javascript" src="{{ elixir('js/app.js') }}"></script>
        <div id="wrapper">
            @include('admin_template.sidebar')
            <div id="page-wrapper" class="gray-bg">
                @include('admin_template.navbar') @yield('content') @include('admin_template.footer')
            </div>
        </div>
        <script type="text/javascript" src="{{ elixir('js/admin/admin.js') }}"></script>
        <script type="text/javascript">
            Loading(true);
            $(window).load(function(){
                Loading(false);
            });
        </script>
        @if(Session::has('success') || Session::has('error'))
            <script>
                $(document).ready(function() {
                    setTimeout(function() {
                        toastr.options = {
                            closeButton: true,
                            progressBar: true,
                            showMethod: 'slideDown',
                            positionClass: 'toast-bottom-left',
                            timeOut: 3000
                        };
                        @if(Session::has('success'))
                            toastr.success('', '{{Session::get("success")}}');
                        @else
                            toastr.error('', '{{Session::get("error")}}');
                        @endif
                    }, 1300);
                });
            </script>
        @endif
    </body>
</html>
