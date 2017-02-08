@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-plus"></i> Companies / Create </h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('companies.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('name')) has-error @endif">
                       <label for="name-field">Name</label>
                    <input type="text" id="name-field" name="name" class="form-control" value="{{ old("name") }}"/>
                       @if($errors->has("name"))
                        <span class="help-block">{{ $errors->first("name") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('about')) has-error @endif">
                       <label for="about-field">About</label>
                    <textarea class="form-control" id="about-field" rows="3" name="about">{{ old("about") }}</textarea>
                       @if($errors->has("about"))
                        <span class="help-block">{{ $errors->first("about") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('logo')) has-error @endif">
                       <label for="logo-field">Logo</label>
                    <textarea class="form-control" id="logo-field" rows="3" name="logo">{{ old("logo") }}</textarea>
                       @if($errors->has("logo"))
                        <span class="help-block">{{ $errors->first("logo") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('hero_image')) has-error @endif">
                       <label for="hero_image-field">Hero_image</label>
                    <textarea class="form-control" id="hero_image-field" rows="3" name="hero_image">{{ old("hero_image") }}</textarea>
                       @if($errors->has("hero_image"))
                        <span class="help-block">{{ $errors->first("hero_image") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('phone')) has-error @endif">
                       <label for="phone-field">Phone</label>
                    <input type="text" id="phone-field" name="phone" class="form-control" value="{{ old("phone") }}"/>
                       @if($errors->has("phone"))
                        <span class="help-block">{{ $errors->first("phone") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('email')) has-error @endif">
                       <label for="email-field">Email</label>
                    <input type="text" id="email-field" name="email" class="form-control" value="{{ old("email") }}"/>
                       @if($errors->has("email"))
                        <span class="help-block">{{ $errors->first("email") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('registered_address')) has-error @endif">
                       <label for="registered_address-field">Registered_address</label>
                    <textarea class="form-control" id="registered_address-field" rows="3" name="registered_address">{{ old("registered_address") }}</textarea>
                       @if($errors->has("registered_address"))
                        <span class="help-block">{{ $errors->first("registered_address") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('established_on')) has-error @endif">
                       <label for="established_on-field">Established_on</label>
                    <input type="text" id="established_on-field" name="established_on" class="form-control date-picker" value="{{ old("established_on") }}"/>
                       @if($errors->has("established_on"))
                        <span class="help-block">{{ $errors->first("established_on") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('status_id')) has-error @endif">
                       <label for="status_id-field">Status_id</label>
                    <input type="text" id="status_id-field" name="status_id" class="form-control" value="{{ old("status_id") }}"/>
                       @if($errors->has("status_id"))
                        <span class="help-block">{{ $errors->first("status_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('status_id')) has-error @endif">
                       <label for="status_id-field">Status_id</label>
                    <input type="text" id="status_id-field" name="status_id" class="form-control" value="{{ old("status_id") }}"/>
                       @if($errors->has("status_id"))
                        <span class="help-block">{{ $errors->first("status_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('type')) has-error @endif">
                       <label for="type-field">Type</label>
                    <input type="text" id="type-field" name="type" class="form-control" value="{{ old("type") }}"/>
                       @if($errors->has("type"))
                        <span class="help-block">{{ $errors->first("type") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('type')) has-error @endif">
                       <label for="type-field">Type</label>
                    <input type="text" id="type-field" name="type" class="form-control" value="{{ old("type") }}"/>
                       @if($errors->has("type"))
                        <span class="help-block">{{ $errors->first("type") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('employee_count')) has-error @endif">
                       <label for="employee_count-field">Employee_count</label>
                    <input type="text" id="employee_count-field" name="employee_count" class="form-control" value="{{ old("employee_count") }}"/>
                       @if($errors->has("employee_count"))
                        <span class="help-block">{{ $errors->first("employee_count") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('client_count')) has-error @endif">
                       <label for="client_count-field">Client_count</label>
                    <input type="text" id="client_count-field" name="client_count" class="form-control" value="{{ old("client_count") }}"/>
                       @if($errors->has("client_count"))
                        <span class="help-block">{{ $errors->first("client_count") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('annual_revenue_start')) has-error @endif">
                       <label for="annual_revenue_start-field">Annual_revenue_start</label>
                    <input type="text" id="annual_revenue_start-field" name="annual_revenue_start" class="form-control" value="{{ old("annual_revenue_start") }}"/>
                       @if($errors->has("annual_revenue_start"))
                        <span class="help-block">{{ $errors->first("annual_revenue_start") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('annual_revenue_end')) has-error @endif">
                       <label for="annual_revenue_end-field">Annual_revenue_end</label>
                    <input type="text" id="annual_revenue_end-field" name="annual_revenue_end" class="form-control" value="{{ old("annual_revenue_end") }}"/>
                       @if($errors->has("annual_revenue_end"))
                        <span class="help-block">{{ $errors->first("annual_revenue_end") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('facebook_url')) has-error @endif">
                       <label for="facebook_url-field">Facebook_url</label>
                    <input type="text" id="facebook_url-field" name="facebook_url" class="form-control" value="{{ old("facebook_url") }}"/>
                       @if($errors->has("facebook_url"))
                        <span class="help-block">{{ $errors->first("facebook_url") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('twitter_url')) has-error @endif">
                       <label for="twitter_url-field">Twitter_url</label>
                    <input type="text" id="twitter_url-field" name="twitter_url" class="form-control" value="{{ old("twitter_url") }}"/>
                       @if($errors->has("twitter_url"))
                        <span class="help-block">{{ $errors->first("twitter_url") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('linkedin_url')) has-error @endif">
                       <label for="linkedin_url-field">Linkedin_url</label>
                    <input type="text" id="linkedin_url-field" name="linkedin_url" class="form-control" value="{{ old("linkedin_url") }}"/>
                       @if($errors->has("linkedin_url"))
                        <span class="help-block">{{ $errors->first("linkedin_url") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('instagram_url')) has-error @endif">
                       <label for="instagram_url-field">Instagram_url</label>
                    <input type="text" id="instagram_url-field" name="instagram_url" class="form-control" value="{{ old("instagram_url") }}"/>
                       @if($errors->has("instagram_url"))
                        <span class="help-block">{{ $errors->first("instagram_url") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('youtube_url')) has-error @endif">
                       <label for="youtube_url-field">Youtube_url</label>
                    <input type="text" id="youtube_url-field" name="youtube_url" class="form-control" value="{{ old("youtube_url") }}"/>
                       @if($errors->has("youtube_url"))
                        <span class="help-block">{{ $errors->first("youtube_url") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('pinterest_url')) has-error @endif">
                       <label for="pinterest_url-field">Pinterest_url</label>
                    <input type="text" id="pinterest_url-field" name="pinterest_url" class="form-control" value="{{ old("pinterest_url") }}"/>
                       @if($errors->has("pinterest_url"))
                        <span class="help-block">{{ $errors->first("pinterest_url") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('google_plus_url')) has-error @endif">
                       <label for="google_plus_url-field">Google_plus_url</label>
                    <input type="text" id="google_plus_url-field" name="google_plus_url" class="form-control" value="{{ old("google_plus_url") }}"/>
                       @if($errors->has("google_plus_url"))
                        <span class="help-block">{{ $errors->first("google_plus_url") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('user_id')) has-error @endif">
                       <label for="user_id-field">User_id</label>
                    <input type="text" id="user_id-field" name="user_id" class="form-control" value="{{ old("user_id") }}"/>
                       @if($errors->has("user_id"))
                        <span class="help-block">{{ $errors->first("user_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('user_id')) has-error @endif">
                       <label for="user_id-field">User_id</label>
                    <input type="text" id="user_id-field" name="user_id" class="form-control" value="{{ old("user_id") }}"/>
                       @if($errors->has("user_id"))
                        <span class="help-block">{{ $errors->first("user_id") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a class="btn btn-link pull-right" href="{{ route('companies.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
                </div>
            </form>

        </div>
    </div>
@endsection
@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script>
  <script>
    $('.date-picker').datepicker({
    });
  </script>
@endsection
