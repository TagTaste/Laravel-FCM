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
        <div class="col-md-8 col-md-push-2">
            <h3>Articles</h3>
            <ul class="list-unstyled">
                @if($ideabook->articles->count())
                    @foreach($ideabook->articles as $article)
                        <li>
                            <div class="row">
                                <div class="col-md-10 col-md-push-1">
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
                    <p class="col-md-10 col-md-push-1">
                        You haven't tagged any articles yet.
                    </p>
                @endif
            </ul>
        </div>

        <div class="col-md-8 col-md-push-2">
            <h3>Albums</h3>
            <ul class="list-unstyled">
                @if($ideabook->albums->count())
                    @foreach($ideabook->albums as $album)
                        <li>
                            <div class="row">
                                <div class="col-md-10 col-md-push-1">
                                    <a href="{{ route("albums.show",$album->id) }}">{{ $album->name }}</a>
                                </div>

                                {{--<div class="col-md-12 text-right">--}}
                                    {{--<a href="{{ route("ideas.remove",$article->id) }}">Remove from Ideabook</a>--}}

                                {{--</div>--}}
                            </div>

                        </li>
                    @endforeach
                @else
                    <p>
                        You haven't tagged any albums yet.
                    </p>
                @endif
            </ul>
        </div>

        <div class="col-md-8 col-md-push-2">
            <h3>Photos</h3>
            <ul class="list-unstyled">
                @if($ideabook->photos->count())
                    @foreach($ideabook->photos as $photo)
                        <li>
                            <div class="row">
                                <div class="col-md-10 col-md-push-1">
                                    <img src="/photos/{{ $photo->id }}.jpg" alt="" width="100px" height="auto">
                                    <a href="{{ route("photos.show",$photo->id) }}">{{ $photo->caption }}</a>
                                </div>

                                {{--<div class="col-md-12 text-right">--}}
                                {{--<a href="{{ route("ideas.remove",$article->id) }}">Remove from Ideabook</a>--}}

                                {{--</div>--}}
                            </div>

                        </li>
                    @endforeach
                @else
                    <p>
                        You haven't tagged any albums yet.
                    </p>
                @endif
            </ul>
        </div>
    </div>


@endsection