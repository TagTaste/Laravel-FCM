@extends('layout')
@section('header')
<div class="page-header">
        <h1>RecipeArticles / Show #{{$recipe_article->id}}</h1>
        <form action="{{ route('recipe_articles.destroy', $recipe_article->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('recipe_articles.edit', $recipe_article->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
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
                     <label for="dish_id">DISH_ID</label>
                     <p class="form-control-static">{{$recipe_article->dish_id}}</p>
                </div>
                    <div class="form-group">
                     <label for="step">STEP</label>
                     <p class="form-control-static">{{$recipe_article->step}}</p>
                </div>
                    <div class="form-group">
                     <label for="content">CONTENT</label>
                     <p class="form-control-static">{{$recipe_article->content}}</p>
                </div>
                    <div class="form-group">
                     <label for="template_id">TEMPLATE_ID</label>
                     <p class="form-control-static">{{$recipe_article->template_id}}</p>
                </div>
                    <div class="form-group">
                     <label for="parent_id">PARENT_ID</label>
                     <p class="form-control-static">{{$recipe_article->parent_id}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('recipe_articles.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection