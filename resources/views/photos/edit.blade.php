@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> Photos / Edit #{{$photo->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('photos.update', $photo->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('caption')) has-error @endif">
                       <label for="caption-field">Caption</label>
                    <input type="text" id="caption-field" name="caption" class="form-control" value="{{ is_null(old("caption")) ? $photo->caption : old("caption") }}"/>
                       @if($errors->has("caption"))
                        <span class="help-block">{{ $errors->first("caption") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('file')) has-error @endif">
                       <label for="file-field">File</label>
                    <input type="text" id="file-field" name="file" class="form-control" value="{{ is_null(old("file")) ? $photo->file : old("file") }}"/>
                       @if($errors->has("file"))
                        <span class="help-block">{{ $errors->first("file") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('album_id')) has-error @endif">
                       <label for="album_id-field">Album_id</label>
                    <input type="text" id="album_id-field" name="album_id" class="form-control" value="{{ is_null(old("album_id")) ? $photo->album_id : old("album_id") }}"/>
                       @if($errors->has("album_id"))
                        <span class="help-block">{{ $errors->first("album_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('album_id')) has-error @endif">
                       <label for="album_id-field">Album_id</label>
                    <input type="text" id="album_id-field" name="album_id" class="form-control" value="{{ is_null(old("album_id")) ? $photo->album_id : old("album_id") }}"/>
                       @if($errors->has("album_id"))
                        <span class="help-block">{{ $errors->first("album_id") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('photos.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
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
