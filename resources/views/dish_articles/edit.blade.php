@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> DishArticles / Edit #{{$dish_article->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('dish_articles.update', $dish_article->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('showcase')) has-error @endif">
                       <label for="showcase-field">Showcase</label>
                    <div class="btn-group" data-toggle="buttons"><label class="btn btn-primary"><input type="radio" value="true" name="showcase-field" id="showcase-field" autocomplete="off"> True</label><label class="btn btn-primary active"><input type="radio" name="showcase-field" value="false" id="showcase-field" autocomplete="off"> False</label></div>
                       @if($errors->has("showcase"))
                        <span class="help-block">{{ $errors->first("showcase") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('hasrecipe')) has-error @endif">
                       <label for="hasrecipe-field">HasRecipe</label>
                    <div class="btn-group" data-toggle="buttons"><label class="btn btn-primary"><input type="radio" value="true" name="hasrecipe-field" id="hasrecipe-field" autocomplete="off"> True</label><label class="btn btn-primary active"><input type="radio" name="hasrecipe-field" value="false" id="hasrecipe-field" autocomplete="off"> False</label></div>
                       @if($errors->has("hasrecipe"))
                        <span class="help-block">{{ $errors->first("hasrecipe") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('article_id')) has-error @endif">
                       <label for="article_id-field">Article_id</label>
                    <input type="text" id="article_id-field" name="article_id" class="form-control" value="{{ is_null(old("article_id")) ? $dish_article->article_id : old("article_id") }}"/>
                       @if($errors->has("article_id"))
                        <span class="help-block">{{ $errors->first("article_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('chef_id')) has-error @endif">
                       <label for="chef_id-field">Chef_id</label>
                    <input type="text" id="chef_id-field" name="chef_id" class="form-control" value="{{ is_null(old("chef_id")) ? $dish_article->chef_id : old("chef_id") }}"/>
                       @if($errors->has("chef_id"))
                        <span class="help-block">{{ $errors->first("chef_id") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('dish_articles.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
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
