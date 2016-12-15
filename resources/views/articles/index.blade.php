@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> Articles
            <a class="btn btn-success pull-right" href="{{ route('articles.new','dish') }}"><i class="glyphicon glyphicon-plus"></i> Add Dish</a>
            <a class="btn btn-success pull-right" href="{{ route('articles.new','recipe') }}"><i class="glyphicon glyphicon-plus"></i> Add Recipe</a>
        </h1>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-9">
            @foreach($dishes as $dish)
                @include($dish->article->template->view,$dish)
                <hr>
            @endforeach
        </div>
    </div>

@endsection