<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>TagTaste</title>

    <!-- Bootstrap core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <!-- <link href="starter-template.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="/css/app.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    @yield('css')
</head>

<body>

    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">g
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">{{ config('app.name', 'Laravel') }}</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class=""><a href="/home"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a></li>
                    <li><a href="{{ route('profiles.index')}}">Profiles</a></li>
                    <li><a href="#">Network</a></li>
                    <li><a href="#">Jobs</a></li>
                    <li><a href="#">Projects</a></li>
                    <li><a href="{{ route('articles.index')}}">My Articles</a></li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <form class="navbar-form " role="search">
                          <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search">
                        </div>
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                    </form>
                </li>
                <!-- Authentication Links -->
                        <!-- @if (Auth::guest())
                            <li><a href="{{ url('/login') }}">Login</a></li>
                            <li><a href="{{ url('/register') }}">Register</a></li>
                            @else -->
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>
                                    <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li class="text-center">
                                        <p style="margin:1em;padding-bottom:1em;border-bottom:thin solid #dedede">{{ Auth::user()->name }} </p>
                                    </li>
                                    @role(('admin'))
                                    <li>
                                        <a href="{{ route("admin.dashboard") }}">Dashboard</a>
                                    </li>
                                    @endrole
                                    <li>
                                        <a href="{{ url('/logout') }}"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>

                            </ul>
                        </li>
                        <!-- @endif -->
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </nav>

        <div class="container">
            @yield('header')
            @yield('content')
        </div><!-- /.container -->
        <div class="text-center text-muted" style="border-top:thin solid #dedede;margin-top:1em;padding:1em;">
            TagTaste © 2016
        </div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <!-- <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script> -->
    <script src="/js/app.js"></script>
    <script src="https://cdn.socket.io/socket.io-1.4.5.js"></script>
    @yield('scripts')

    <script>
        Loading(false);
        $(document).ready(function(){
            var socket = io('{{ env('APP_URL') }}:{{env('SOCKETIO_PORT')}}');
            socket.on("notifications", function(message){
                toastr.success(message.message[0]);

            });
        });
    </script>
</body>
</html>
