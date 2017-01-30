@extends('layout')
@section('header')
<div class="page-header">
        <h1>Ideabooks / Show #{{$ideabook->id}}</h1>
        <form action="{{ route('ideabooks.destroy', $ideabook->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('ideabooks.edit', $ideabook->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                <button type="submit" class="btn btn-danger">Delete <i class="glyphicon glyphicon-trash"></i></button>
            </div>
        </form>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">

            <form action="#">
                <div class="form-group">
                     <label for="name">NAME</label>
                     <p class="form-control-static">{{$ideabook->name}}</p>
                </div>
                    <div class="form-group">
                     <label for="description">DESCRIPTION</label>
                     <p class="form-control-static">{{$ideabook->description}}</p>
                </div>
                    <div class="form-group">
                     <label for="privacy_id">PRIVACY</label>
                     <p class="form-control-static">{{$ideabook->privacy->name}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('ideabooks.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
        <div class="col-md-8">
            <h4>Albums</h4>
            <ul>
                @foreach($ideabook->albums as $album)
                    <li><a href="{{ route("albums.show",$album->id) }}">{{ $album->name }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>

@endsection