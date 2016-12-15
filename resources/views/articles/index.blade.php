@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> My Articles
            <a class="btn btn-success pull-right" href="{{ route('articles.new','dish') }}"><i class="glyphicon glyphicon-plus"></i> Add Dish</a>
            <a class="btn btn-success pull-right" href="{{ route('articles.new','recipe') }}"><i class="glyphicon glyphicon-plus"></i> Add Recipe</a>
        </h1>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-9">
            @if($articles->count())
                @foreach($articles as $article)
                    @include($article->template->view,['dish'=>$article->dish, 'title'=>$article->title])
                    <hr>
                @endforeach
            @else
                <p>You haven't written any articles yet.</p>
            @endif
        </div>
    </div>

@endsection