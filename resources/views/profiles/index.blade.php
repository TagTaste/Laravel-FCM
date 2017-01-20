@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> Profiles
            <a class="btn btn-success pull-right" href="{{ route('profiles.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($profiles->count())
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NAME</th>
                        <th>TAGLINE</th>
                        <th>ABOUT</th>
                        <th>IMAGE</th>
                        <th>HERO_IMAGE</th>
                        <th>PHONE</th>
                        <th>ADDRESS</th>
                        <th>DOB</th>
                        <th>INTERESTS</th>
                        <th>MARITAL_STATUS</th>
                        <th>WEBSITE_URL</th>
                        <th>BLOG_URL</th>
                        <th>FACEBOOK_URL</th>
                        <th>LINKEDIN_URL</th>
                        <th>INSTAGRAM_LINK</th>
                        <th>YOUTUBE_CHANNEL</th>
                        <th>FOLLOWERS</th>
                        <th>FOLLOWING</th>
                        <th>USER_ID</th>
                            <th class="text-right">OPTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($profiles as $profile)
                            <tr>
                                <td>{{$profile->id}}</td>
                                <td>{{$profile->name}}</td>
                    <td>{{$profile->tagline}}</td>
                    <td>{{$profile->about}}</td>
                    <td>{{$profile->image}}</td>
                    <td>{{$profile->hero_image}}</td>
                    <td>{{$profile->phone}}</td>
                    <td>{{$profile->address}}</td>
                    <td>{{$profile->dob}}</td>
                    <td>{{$profile->interests}}</td>
                    <td>{{$profile->marital_status}}</td>
                    <td>{{$profile->website_url}}</td>
                    <td>{{$profile->blog_url}}</td>
                    <td>{{$profile->facebook_url}}</td>
                    <td>{{$profile->linkedin_url}}</td>
                    <td>{{$profile->instagram_link}}</td>
                    <td>{{$profile->youtube_channel}}</td>
                    <td>{{$profile->followers}}</td>
                    <td>{{$profile->following}}</td>
                    <td>{{$profile->user_id}}</td>
                                <td class="text-right">
                                    <a class="btn btn-xs btn-primary" href="{{ route('profiles.show', $profile->id) }}"><i class="glyphicon glyphicon-eye-open"></i> View</a>
                                    <a class="btn btn-xs btn-warning" href="{{ route('profiles.edit', $profile->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                                    <form action="{{ route('profiles.destroy', $profile->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $profiles->render() !!}
            @else
                <h3 class="text-center alert alert-info">Empty!</h3>
            @endif

        </div>
    </div>

@endsection