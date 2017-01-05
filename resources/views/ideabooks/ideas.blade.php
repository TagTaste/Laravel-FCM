@extends('layout')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css"
          rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> Ideas</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-6 col-md-push-3">
            <ul class="list-unstyled">
                @if($articles->count())
                    @foreach($articles as $article)
                        <li>
                            <div class="row">
                                <div class="col-md-12">
                                    <h2><a href="{{ route("articles.show",$article->id) }}">{{ $article->title }}</a>
                                    </h2>
                                </div>

                                <div class="col-md-12 text-justify">
                                    <p>{{ $article->getContent() }}</p>
                                </div>

                                <div class="col-md-12 text-right">
                                    <a href="{{ route("ideas.remove",$article->id) }}">Remove from Ideabook</a>

                                </div>
                            </div>

                        </li>
                    @endforeach
                @else
                    <p>
                        You haven't saved any idea yet.
                    </p>
                @endif
            </ul>
        </div>
    </div>


@endsection