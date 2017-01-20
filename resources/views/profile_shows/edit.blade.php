@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> ProfileShows / Edit #{{$profile_show->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('profile_shows.update', $profile_show->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('title')) has-error @endif">
                       <label for="title-field">Title</label>
                    <input type="text" id="title-field" name="title" class="form-control" value="{{ is_null(old("title")) ? $profile_show->title : old("title") }}"/>
                       @if($errors->has("title"))
                        <span class="help-block">{{ $errors->first("title") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('description')) has-error @endif">
                       <label for="description-field">Description</label>
                    <textarea class="form-control" id="description-field" rows="3" name="description">{{ is_null(old("description")) ? $profile_show->description : old("description") }}</textarea>
                       @if($errors->has("description"))
                        <span class="help-block">{{ $errors->first("description") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('channel')) has-error @endif">
                       <label for="channel-field">Channel</label>
                    <input type="text" id="channel-field" name="channel" class="form-control" value="{{ is_null(old("channel")) ? $profile_show->channel : old("channel") }}"/>
                       @if($errors->has("channel"))
                        <span class="help-block">{{ $errors->first("channel") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('current')) has-error @endif">
                       <label for="current-field">Current</label>
                    <div class="btn-group" data-toggle="buttons"><label class="btn btn-primary"><input type="radio" value="true" name="current-field" id="current-field" autocomplete="off"> True</label><label class="btn btn-primary active"><input type="radio" name="current-field" value="false" id="current-field" autocomplete="off"> False</label></div>
                       @if($errors->has("current"))
                        <span class="help-block">{{ $errors->first("current") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('start_date')) has-error @endif">
                       <label for="start_date-field">Start_date</label>
                    <input type="text" id="start_date-field" name="start_date" class="form-control date-picker" value="{{ is_null(old("start_date")) ? $profile_show->start_date : old("start_date") }}"/>
                       @if($errors->has("start_date"))
                        <span class="help-block">{{ $errors->first("start_date") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('end_date')) has-error @endif">
                       <label for="end_date-field">End_date</label>
                    <input type="text" id="end_date-field" name="end_date" class="form-control date-picker" value="{{ is_null(old("end_date")) ? $profile_show->end_date : old("end_date") }}"/>
                       @if($errors->has("end_date"))
                        <span class="help-block">{{ $errors->first("end_date") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('url')) has-error @endif">
                       <label for="url-field">Url</label>
                    <input type="text" id="url-field" name="url" class="form-control" value="{{ is_null(old("url")) ? $profile_show->url : old("url") }}"/>
                       @if($errors->has("url"))
                        <span class="help-block">{{ $errors->first("url") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('appeared_as')) has-error @endif">
                       <label for="appeared_as-field">Appeared_as</label>
                    <input type="text" id="appeared_as-field" name="appeared_as" class="form-control" value="{{ is_null(old("appeared_as")) ? $profile_show->appeared_as : old("appeared_as") }}"/>
                       @if($errors->has("appeared_as"))
                        <span class="help-block">{{ $errors->first("appeared_as") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('profile_id')) has-error @endif">
                       <label for="profile_id-field">Profile_id</label>
                    <input type="text" id="profile_id-field" name="profile_id" class="form-control" value="{{ is_null(old("profile_id")) ? $profile_show->profile_id : old("profile_id") }}"/>
                       @if($errors->has("profile_id"))
                        <span class="help-block">{{ $errors->first("profile_id") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('profile_shows.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
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
