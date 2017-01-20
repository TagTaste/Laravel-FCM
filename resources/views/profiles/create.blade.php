@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-plus"></i> Profiles / Create </h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('profiles.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('name')) has-error @endif">
                       <label for="name-field">Name</label>
                    <input type="text" id="name-field" name="name" class="form-control" value="{{ old("name") }}"/>
                       @if($errors->has("name"))
                        <span class="help-block">{{ $errors->first("name") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('tagline')) has-error @endif">
                       <label for="tagline-field">Tagline</label>
                    <textarea class="form-control" id="tagline-field" rows="3" name="tagline">{{ old("tagline") }}</textarea>
                       @if($errors->has("tagline"))
                        <span class="help-block">{{ $errors->first("tagline") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('about')) has-error @endif">
                       <label for="about-field">About</label>
                    <textarea class="form-control" id="about-field" rows="3" name="about">{{ old("about") }}</textarea>
                       @if($errors->has("about"))
                        <span class="help-block">{{ $errors->first("about") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('image')) has-error @endif">
                       <label for="image-field">Image</label>
                    <input type="text" id="image-field" name="image" class="form-control" value="{{ old("image") }}"/>
                       @if($errors->has("image"))
                        <span class="help-block">{{ $errors->first("image") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('hero_image')) has-error @endif">
                       <label for="hero_image-field">Hero_image</label>
                    <input type="text" id="hero_image-field" name="hero_image" class="form-control" value="{{ old("hero_image") }}"/>
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
                    <div class="form-group @if($errors->has('address')) has-error @endif">
                       <label for="address-field">Address</label>
                    <textarea class="form-control" id="address-field" rows="3" name="address">{{ old("address") }}</textarea>
                       @if($errors->has("address"))
                        <span class="help-block">{{ $errors->first("address") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('dob')) has-error @endif">
                       <label for="dob-field">Dob</label>
                    <input type="text" id="dob-field" name="dob" class="form-control date-picker" value="{{ old("dob") }}"/>
                       @if($errors->has("dob"))
                        <span class="help-block">{{ $errors->first("dob") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('interests')) has-error @endif">
                       <label for="interests-field">Interests</label>
                    <textarea class="form-control" id="interests-field" rows="3" name="interests">{{ old("interests") }}</textarea>
                       @if($errors->has("interests"))
                        <span class="help-block">{{ $errors->first("interests") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('marital_status')) has-error @endif">
                       <label for="marital_status-field">Marital_status</label>
                    <input type="text" id="marital_status-field" name="marital_status" class="form-control" value="{{ old("marital_status") }}"/>
                       @if($errors->has("marital_status"))
                        <span class="help-block">{{ $errors->first("marital_status") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('website_url')) has-error @endif">
                       <label for="website_url-field">Website_url</label>
                    <input type="text" id="website_url-field" name="website_url" class="form-control" value="{{ old("website_url") }}"/>
                       @if($errors->has("website_url"))
                        <span class="help-block">{{ $errors->first("website_url") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('blog_url')) has-error @endif">
                       <label for="blog_url-field">Blog_url</label>
                    <input type="text" id="blog_url-field" name="blog_url" class="form-control" value="{{ old("blog_url") }}"/>
                       @if($errors->has("blog_url"))
                        <span class="help-block">{{ $errors->first("blog_url") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('facebook_url')) has-error @endif">
                       <label for="facebook_url-field">Facebook_url</label>
                    <input type="text" id="facebook_url-field" name="facebook_url" class="form-control" value="{{ old("facebook_url") }}"/>
                       @if($errors->has("facebook_url"))
                        <span class="help-block">{{ $errors->first("facebook_url") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('linkedin_url')) has-error @endif">
                       <label for="linkedin_url-field">Linkedin_url</label>
                    <input type="text" id="linkedin_url-field" name="linkedin_url" class="form-control" value="{{ old("linkedin_url") }}"/>
                       @if($errors->has("linkedin_url"))
                        <span class="help-block">{{ $errors->first("linkedin_url") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('instagram_link')) has-error @endif">
                       <label for="instagram_link-field">Instagram_link</label>
                    <input type="text" id="instagram_link-field" name="instagram_link" class="form-control" value="{{ old("instagram_link") }}"/>
                       @if($errors->has("instagram_link"))
                        <span class="help-block">{{ $errors->first("instagram_link") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('youtube_channel')) has-error @endif">
                       <label for="youtube_channel-field">Youtube_channel</label>
                    <input type="text" id="youtube_channel-field" name="youtube_channel" class="form-control" value="{{ old("youtube_channel") }}"/>
                       @if($errors->has("youtube_channel"))
                        <span class="help-block">{{ $errors->first("youtube_channel") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('followers')) has-error @endif">
                       <label for="followers-field">Followers</label>
                    <input type="text" id="followers-field" name="followers" class="form-control" value="{{ old("followers") }}"/>
                       @if($errors->has("followers"))
                        <span class="help-block">{{ $errors->first("followers") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('following')) has-error @endif">
                       <label for="following-field">Following</label>
                    <input type="text" id="following-field" name="following" class="form-control" value="{{ old("following") }}"/>
                       @if($errors->has("following"))
                        <span class="help-block">{{ $errors->first("following") }}</span>
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
                    <a class="btn btn-link pull-right" href="{{ route('profiles.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
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
