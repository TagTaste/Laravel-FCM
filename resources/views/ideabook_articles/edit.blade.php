@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> IdeabookArticles / Edit #{{$ideabook_article->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('ideabook_articles.update', $ideabook_article->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('ideabook_id')) has-error @endif">
                       <label for="ideabook_id-field">Ideabook_id</label>
                    <input type="text" id="ideabook_id-field" name="ideabook_id" class="form-control" value="{{ is_null(old("ideabook_id")) ? $ideabook_article->ideabook_id : old("ideabook_id") }}"/>
                       @if($errors->has("ideabook_id"))
                        <span class="help-block">{{ $errors->first("ideabook_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('article_id')) has-error @endif">
                       <label for="article_id-field">Article_id</label>
                    <input type="text" id="article_id-field" name="article_id" class="form-control" value="{{ is_null(old("article_id")) ? $ideabook_article->article_id : old("article_id") }}"/>
                       @if($errors->has("article_id"))
                        <span class="help-block">{{ $errors->first("article_id") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('ideabook_articles.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
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
