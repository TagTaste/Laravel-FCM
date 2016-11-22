@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-plus"></i> RecipeArticles / Create </h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('recipe_articles.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('dish_id')) has-error @endif">
                       <label for="dish_id-field">Dish_id</label>
                    <input type="text" id="dish_id-field" name="dish_id" class="form-control" value="{{ old("dish_id") }}"/>
                       @if($errors->has("dish_id"))
                        <span class="help-block">{{ $errors->first("dish_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('step')) has-error @endif">
                       <label for="step-field">Step</label>
                    <input type="text" id="step-field" name="step" class="form-control" value="{{ old("step") }}"/>
                       @if($errors->has("step"))
                        <span class="help-block">{{ $errors->first("step") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('content')) has-error @endif">
                       <label for="content-field">Content</label>
                    <textarea class="form-control" id="content-field" rows="3" name="content">{{ old("content") }}</textarea>
                       @if($errors->has("content"))
                        <span class="help-block">{{ $errors->first("content") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('template_id')) has-error @endif">
                       <label for="template_id-field">Template_id</label>
                    <input type="text" id="template_id-field" name="template_id" class="form-control" value="{{ old("template_id") }}"/>
                       @if($errors->has("template_id"))
                        <span class="help-block">{{ $errors->first("template_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('parent_id')) has-error @endif">
                       <label for="parent_id-field">Parent_id</label>
                    <input type="text" id="parent_id-field" name="parent_id" class="form-control" value="{{ old("parent_id") }}"/>
                       @if($errors->has("parent_id"))
                        <span class="help-block">{{ $errors->first("parent_id") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a class="btn btn-link pull-right" href="{{ route('recipe_articles.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
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
