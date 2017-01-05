@extends('layout')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> Ideas</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
       <div class="col-md-12">
           <ul>
               @foreach($articles as $article)
                   <li>
                       <a href="{{ route("articles.show",$article->id) }}">{{ $article->title }}</a>
                       <a href="{{ route("ideas.remove",$article->id) }}">x</a>

                   </li>
               @endforeach
           </ul>
       </div>
    </div>


@endsection