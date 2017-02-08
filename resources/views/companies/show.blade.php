@extends('layout')
@section('header')
<div class="page-header">
        <h1>Companies / Show #{{$company->id}}</h1>
        <form action="{{ route('companies.destroy', $company->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('companies.edit', $company->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
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
                     <p class="form-control-static">{{$company->name}}</p>
                </div>
                    <div class="form-group">
                     <label for="about">ABOUT</label>
                     <p class="form-control-static">{{$company->about}}</p>
                </div>
                    <div class="form-group">
                     <label for="logo">LOGO</label>
                     <p class="form-control-static">{{$company->logo}}</p>
                </div>
                    <div class="form-group">
                     <label for="hero_image">HERO_IMAGE</label>
                     <p class="form-control-static">{{$company->hero_image}}</p>
                </div>
                    <div class="form-group">
                     <label for="phone">PHONE</label>
                     <p class="form-control-static">{{$company->phone}}</p>
                </div>
                    <div class="form-group">
                     <label for="email">EMAIL</label>
                     <p class="form-control-static">{{$company->email}}</p>
                </div>
                    <div class="form-group">
                     <label for="registered_address">REGISTERED_ADDRESS</label>
                     <p class="form-control-static">{{$company->registered_address}}</p>
                </div>
                    <div class="form-group">
                     <label for="established_on">ESTABLISHED_ON</label>
                     <p class="form-control-static">{{$company->established_on}}</p>
                </div>
                    <div class="form-group">
                     <label for="status_id">STATUS_ID</label>
                     <p class="form-control-static">{{$company->status_id}}</p>
                </div>
                    <div class="form-group">
                     <label for="status_id">STATUS_ID</label>
                     <p class="form-control-static">{{$company->status_id}}</p>
                </div>
                    <div class="form-group">
                     <label for="type">TYPE</label>
                     <p class="form-control-static">{{$company->type}}</p>
                </div>
                    <div class="form-group">
                     <label for="type">TYPE</label>
                     <p class="form-control-static">{{$company->type}}</p>
                </div>
                    <div class="form-group">
                     <label for="employee_count">EMPLOYEE_COUNT</label>
                     <p class="form-control-static">{{$company->employee_count}}</p>
                </div>
                    <div class="form-group">
                     <label for="client_count">CLIENT_COUNT</label>
                     <p class="form-control-static">{{$company->client_count}}</p>
                </div>
                    <div class="form-group">
                     <label for="annual_revenue_start">ANNUAL_REVENUE_START</label>
                     <p class="form-control-static">{{$company->annual_revenue_start}}</p>
                </div>
                    <div class="form-group">
                     <label for="annual_revenue_end">ANNUAL_REVENUE_END</label>
                     <p class="form-control-static">{{$company->annual_revenue_end}}</p>
                </div>
                    <div class="form-group">
                     <label for="facebook_url">FACEBOOK_URL</label>
                     <p class="form-control-static">{{$company->facebook_url}}</p>
                </div>
                    <div class="form-group">
                     <label for="twitter_url">TWITTER_URL</label>
                     <p class="form-control-static">{{$company->twitter_url}}</p>
                </div>
                    <div class="form-group">
                     <label for="linkedin_url">LINKEDIN_URL</label>
                     <p class="form-control-static">{{$company->linkedin_url}}</p>
                </div>
                    <div class="form-group">
                     <label for="instagram_url">INSTAGRAM_URL</label>
                     <p class="form-control-static">{{$company->instagram_url}}</p>
                </div>
                    <div class="form-group">
                     <label for="youtube_url">YOUTUBE_URL</label>
                     <p class="form-control-static">{{$company->youtube_url}}</p>
                </div>
                    <div class="form-group">
                     <label for="pinterest_url">PINTEREST_URL</label>
                     <p class="form-control-static">{{$company->pinterest_url}}</p>
                </div>
                    <div class="form-group">
                     <label for="google_plus_url">GOOGLE_PLUS_URL</label>
                     <p class="form-control-static">{{$company->google_plus_url}}</p>
                </div>
                    <div class="form-group">
                     <label for="user_id">USER_ID</label>
                     <p class="form-control-static">{{$company->user_id}}</p>
                </div>
                    <div class="form-group">
                     <label for="user_id">USER_ID</label>
                     <p class="form-control-static">{{$company->user_id}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('companies.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection