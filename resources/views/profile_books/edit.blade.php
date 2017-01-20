@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> ProfileBooks / Edit #{{$profile_book->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('profile_books.update', $profile_book->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('title')) has-error @endif">
                       <label for="title-field">Title</label>
                    <input type="text" id="title-field" name="title" class="form-control" value="{{ is_null(old("title")) ? $profile_book->title : old("title") }}"/>
                       @if($errors->has("title"))
                        <span class="help-block">{{ $errors->first("title") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('description')) has-error @endif">
                       <label for="description-field">Description</label>
                    <textarea class="form-control" id="description-field" rows="3" name="description">{{ is_null(old("description")) ? $profile_book->description : old("description") }}</textarea>
                       @if($errors->has("description"))
                        <span class="help-block">{{ $errors->first("description") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('publisher')) has-error @endif">
                       <label for="publisher-field">Publisher</label>
                    <textarea class="form-control" id="publisher-field" rows="3" name="publisher">{{ is_null(old("publisher")) ? $profile_book->publisher : old("publisher") }}</textarea>
                       @if($errors->has("publisher"))
                        <span class="help-block">{{ $errors->first("publisher") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('release_date')) has-error @endif">
                       <label for="release_date-field">Release_date</label>
                    <input type="text" id="release_date-field" name="release_date" class="form-control date-picker" value="{{ is_null(old("release_date")) ? $profile_book->release_date : old("release_date") }}"/>
                       @if($errors->has("release_date"))
                        <span class="help-block">{{ $errors->first("release_date") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('url')) has-error @endif">
                       <label for="url-field">Url</label>
                    <input type="text" id="url-field" name="url" class="form-control" value="{{ is_null(old("url")) ? $profile_book->url : old("url") }}"/>
                       @if($errors->has("url"))
                        <span class="help-block">{{ $errors->first("url") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('isbn')) has-error @endif">
                       <label for="isbn-field">Isbn</label>
                    <input type="text" id="isbn-field" name="isbn" class="form-control" value="{{ is_null(old("isbn")) ? $profile_book->isbn : old("isbn") }}"/>
                       @if($errors->has("isbn"))
                        <span class="help-block">{{ $errors->first("isbn") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('profile_id')) has-error @endif">
                       <label for="profile_id-field">Profile_id</label>
                    <input type="text" id="profile_id-field" name="profile_id" class="form-control" value="{{ is_null(old("profile_id")) ? $profile_book->profile_id : old("profile_id") }}"/>
                       @if($errors->has("profile_id"))
                        <span class="help-block">{{ $errors->first("profile_id") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('profile_books.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
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
