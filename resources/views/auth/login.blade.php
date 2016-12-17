@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-5 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading text-center">Login</div>
                    <div class="panel-body">
                        <form class="form-horizontal col-md-12" role="form" method="POST"
                              action="{{ url('/verifyLogin') }}" enctype="multipart/form-data">

                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="">E-Mail Address</label>

                                <input id="email" type="email" class="form-control" name="email"
                                       value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif

                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="">Password</label>
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif

                            </div>


                            <div class="form-group">
                                <div class="col-md-12 ">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember"> Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        Login
                                    </button>

                                    <a class="btn btn-link" href="{{ url('/password/reset') }}">
                                        Forgot Your Password?
                                    </a>
                                </div>
                            </div>
                            <div class="form-group text-center" style="border-top:thin solid #dedede">
                                <div class="col-md-12" style="margin-top:2em;">
                                    <a class="btn btn-primary"
                                       href="{{ route('social.login', ['facebook']) }}">Facebook</a>
                                    <a class="btn btn-primary" href="{{ route('social.login', ['google']) }}">Google</a>
                                    <a class="btn btn-primary" href="{{ route('social.login', ['instagram']) }}">Instagram</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
