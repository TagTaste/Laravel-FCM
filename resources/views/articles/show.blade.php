@extends('layout')
@section('header')
<div class="page-header">
        <h1>Articles / Show #{{$article->id}}</h1>
        <form action="{{ route('articles.destroy', $article->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('articles.edit', $article->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
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
                     <p class="form-control-static">{{$article->title}}</p>
                </div>
                    <div class="form-group">
                     <label for="author_id">AUTHOR_ID</label>
                     <p class="form-control-static">{{$article->author_id}}</p>
                </div>
                    <div class="form-group">
                     <label for="privacy_id">PRIVACY_ID</label>
                     <p class="form-control-static">{{$article->privacy_id}}</p>
                </div>
                    <div class="form-group">
                     <label for="comments_enabled">COMMENTS_ENABLED</label>
                     <p class="form-control-static">{{$article->comments_enabled}}</p>
                </div>
                    <div class="form-group">
                     <label for="status">STATUS</label>
                     <p class="form-control-static">{{$article->status}}</p>
                </div>
                    <div class="form-group">
                     <label for="template_id">TEMPLATE_ID</label>
                     <p class="form-control-static">{{$article->template_id}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('articles.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection