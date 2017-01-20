@extends('layout')
@section('header')
<div class="page-header">
        <h1>ProfileShows / Show #{{$profile_show->id}}</h1>
        <form action="{{ route('profile_shows.destroy', $profile_show->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('profile_shows.edit', $profile_show->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
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
                     <label for="title">TITLE</label>
                     <p class="form-control-static">{{$profile_show->title}}</p>
                </div>
                    <div class="form-group">
                     <label for="description">DESCRIPTION</label>
                     <p class="form-control-static">{{$profile_show->description}}</p>
                </div>
                    <div class="form-group">
                     <label for="channel">CHANNEL</label>
                     <p class="form-control-static">{{$profile_show->channel}}</p>
                </div>
                    <div class="form-group">
                     <label for="current">CURRENT</label>
                     <p class="form-control-static">{{$profile_show->current}}</p>
                </div>
                    <div class="form-group">
                     <label for="start_date">START_DATE</label>
                     <p class="form-control-static">{{$profile_show->start_date}}</p>
                </div>
                    <div class="form-group">
                     <label for="end_date">END_DATE</label>
                     <p class="form-control-static">{{$profile_show->end_date}}</p>
                </div>
                    <div class="form-group">
                     <label for="url">URL</label>
                     <p class="form-control-static">{{$profile_show->url}}</p>
                </div>
                    <div class="form-group">
                     <label for="appeared_as">APPEARED_AS</label>
                     <p class="form-control-static">{{$profile_show->appeared_as}}</p>
                </div>
                    <div class="form-group">
                     <label for="profile_id">PROFILE_ID</label>
                     <p class="form-control-static">{{$profile_show->profile_id}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('profile_shows.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection