@extends('layout')
@section('header')
<div class="page-header">
        <h1>BlogArticles / Show #{{$blog_article->id}}</h1>
        <form action="{{ route('blog_articles.destroy', $blog_article->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('blog_articles.edit', $blog_article->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
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
                     <label for="content">CONTENT</label>
                     <p class="form-control-static">{{$blog_article->content}}</p>
                </div>
                    <div class="form-group">
                     <label for="image">IMAGE</label>
                     <p class="form-control-static">{{$blog_article->image}}</p>
                </div>
                    <div class="form-group">
                     <label for="article_id">ARTICLE_ID</label>
                     <p class="form-control-static">{{$blog_article->article_id}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('blog_articles.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection