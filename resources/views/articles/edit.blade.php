@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> Articles / Edit #{{$article->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('articles.update', $article->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('title')) has-error @endif">
                       <label for="title-field">Title</label>
                    <input type="text" id="title-field" name="title" class="form-control" value="{{ is_null(old("title")) ? $article->title : old("title") }}"/>
                       @if($errors->has("title"))
                        <span class="help-block">{{ $errors->first("title") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('author_id')) has-error @endif">
                       <label for="author_id-field">Author_id</label>
                    <input type="text" id="author_id-field" name="author_id" class="form-control" value="{{ is_null(old("author_id")) ? $article->author_id : old("author_id") }}"/>
                       @if($errors->has("author_id"))
                        <span class="help-block">{{ $errors->first("author_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('privacy_id')) has-error @endif">
                       <label for="privacy_id-field">Privacy_id</label>
                    <input type="text" id="privacy_id-field" name="privacy_id" class="form-control" value="{{ is_null(old("privacy_id")) ? $article->privacy_id : old("privacy_id") }}"/>
                       @if($errors->has("privacy_id"))
                        <span class="help-block">{{ $errors->first("privacy_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('comments_enabled')) has-error @endif">
                       <label for="comments_enabled-field">Comments_enabled</label>
                    <div class="btn-group" data-toggle="buttons"><label class="btn btn-primary"><input type="radio" value="true" name="comments_enabled-field" id="comments_enabled-field" autocomplete="off"> True</label><label class="btn btn-primary active"><input type="radio" name="comments_enabled-field" value="false" id="comments_enabled-field" autocomplete="off"> False</label></div>
                       @if($errors->has("comments_enabled"))
                        <span class="help-block">{{ $errors->first("comments_enabled") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('status')) has-error @endif">
                       <label for="status-field">Status</label>
                    <input type="text" id="status-field" name="status" class="form-control" value="{{ is_null(old("status")) ? $article->status : old("status") }}"/>
                       @if($errors->has("status"))
                        <span class="help-block">{{ $errors->first("status") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('template_id')) has-error @endif">
                       <label for="template_id-field">Template_id</label>
                    <input type="text" id="template_id-field" name="template_id" class="form-control" value="{{ is_null(old("template_id")) ? $article->template_id : old("template_id") }}"/>
                       @if($errors->has("template_id"))
                        <span class="help-block">{{ $errors->first("template_id") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('articles.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
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
