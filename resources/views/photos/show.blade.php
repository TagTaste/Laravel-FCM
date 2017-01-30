@extends('layout')
@section('header')
<div class="page-header">
        <h1>Photos / Show #{{$photo->id}}</h1>
        <form action="{{ route('photos.destroy', $photo->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('photos.edit', $photo->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
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
                     <label for="caption">CAPTION</label>
                     <p class="form-control-static">{{$photo->caption}}</p>
                </div>
                    <div class="form-group">
                     <label for="file">FILE</label>
                     <p class="form-control-static">{{$photo->file}}</p>
                </div>
                    <div class="form-group">
                     <label for="album_id">ALBUM_ID</label>
                     <p class="form-control-static">{{$photo->album_id}}</p>
                </div>
                    <div class="form-group">
                     <label for="album_id">ALBUM_ID</label>
                     <p class="form-control-static">{{$photo->album_id}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('photos.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection