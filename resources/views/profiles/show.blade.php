@extends('layout')
@section('header')
<div class="page-header">
        <h1>Profiles / Show #{{$profile->id}}</h1>
        <form action="{{ route('profiles.destroy', $profile->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('profiles.edit', $profile->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                <button type="submit" class="btn btn-danger">Delete <i class="glyphicon glyphicon-trash"></i></button>
            </div>
        </form>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <form action="#">
                <div class="form-group">
                    <label for="nome">ID</label>
                    <p class="form-control-static"></p>
                </div>
                <div class="form-group">
                     <label for="name">NAME</label>
                     <p class="form-control-static">{{$profile->name}}</p>
                </div>
                    <div class="form-group">
                     <label for="tagline">TAGLINE</label>
                     <p class="form-control-static">{{$profile->tagline}}</p>
                </div>
                    <div class="form-group">
                     <label for="about">ABOUT</label>
                     <p class="form-control-static">{{$profile->about}}</p>
                </div>
                    <div class="form-group">
                     <label for="image">IMAGE</label>
                     <p class="form-control-static">{{$profile->image}}</p>
                </div>
                    <div class="form-group">
                     <label for="hero_image">HERO_IMAGE</label>
                     <p class="form-control-static">{{$profile->hero_image}}</p>
                </div>
                    <div class="form-group">
                     <label for="phone">PHONE</label>
                     <p class="form-control-static">{{$profile->phone}}</p>
                </div>
                    <div class="form-group">
                     <label for="address">ADDRESS</label>
                     <p class="form-control-static">{{$profile->address}}</p>
                </div>
                    <div class="form-group">
                     <label for="dob">DOB</label>
                     <p class="form-control-static">{{$profile->dob}}</p>
                </div>
                    <div class="form-group">
                     <label for="interests">INTERESTS</label>
                     <p class="form-control-static">{{$profile->interests}}</p>
                </div>
                    <div class="form-group">
                     <label for="marital_status">MARITAL_STATUS</label>
                     <p class="form-control-static">{{$profile->marital_status}}</p>
                </div>
                    <div class="form-group">
                     <label for="website_url">WEBSITE_URL</label>
                     <p class="form-control-static">{{$profile->website_url}}</p>
                </div>
                    <div class="form-group">
                     <label for="blog_url">BLOG_URL</label>
                     <p class="form-control-static">{{$profile->blog_url}}</p>
                </div>
                    <div class="form-group">
                     <label for="facebook_url">FACEBOOK_URL</label>
                     <p class="form-control-static">{{$profile->facebook_url}}</p>
                </div>
                    <div class="form-group">
                     <label for="linkedin_url">LINKEDIN_URL</label>
                     <p class="form-control-static">{{$profile->linkedin_url}}</p>
                </div>
                    <div class="form-group">
                     <label for="instagram_link">INSTAGRAM_LINK</label>
                     <p class="form-control-static">{{$profile->instagram_link}}</p>
                </div>
                    <div class="form-group">
                     <label for="youtube_channel">YOUTUBE_CHANNEL</label>
                     <p class="form-control-static">{{$profile->youtube_channel}}</p>
                </div>
                    <div class="form-group">
                     <label for="followers">FOLLOWERS</label>
                     <p class="form-control-static">{{$profile->followers}}</p>
                </div>
                    <div class="form-group">
                     <label for="following">FOLLOWING</label>
                     <p class="form-control-static">{{$profile->following}}</p>
                </div>
                    <div class="form-group">
                     <label for="user_id">USER_ID</label>
                     <p class="form-control-static">{{$profile->user_id}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('profiles.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection