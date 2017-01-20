@extends('layout')
@section('header')
<div class="page-header">
        <h1>ProfileBooks / Show #{{$profile_book->id}}</h1>
        <form action="{{ route('profile_books.destroy', $profile_book->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('profile_books.edit', $profile_book->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
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
                     <p class="form-control-static">{{$profile_book->title}}</p>
                </div>
                    <div class="form-group">
                     <label for="description">DESCRIPTION</label>
                     <p class="form-control-static">{{$profile_book->description}}</p>
                </div>
                    <div class="form-group">
                     <label for="publisher">PUBLISHER</label>
                     <p class="form-control-static">{{$profile_book->publisher}}</p>
                </div>
                    <div class="form-group">
                     <label for="release_date">RELEASE_DATE</label>
                     <p class="form-control-static">{{$profile_book->release_date}}</p>
                </div>
                    <div class="form-group">
                     <label for="url">URL</label>
                     <p class="form-control-static">{{$profile_book->url}}</p>
                </div>
                    <div class="form-group">
                     <label for="isbn">ISBN</label>
                     <p class="form-control-static">{{$profile_book->isbn}}</p>
                </div>
                    <div class="form-group">
                     <label for="profile_id">PROFILE_ID</label>
                     <p class="form-control-static">{{$profile_book->profile_id}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('profile_books.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection