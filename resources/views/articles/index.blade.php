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
        <div class="col-md-12">
            @if($articles->count())
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>TITLE</th>
                        <th>AUTHOR_ID</th>
                        <th>PRIVACY_ID</th>
                        <th>COMMENTS_ENABLED</th>
                        <th>STATUS</th>
                        <th>TEMPLATE_ID</th>
                            <th class="text-right">OPTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($articles as $article)
                            <tr>
                                <td>{{$article->id}}</td>
                                <td>{{$article->title}}</td>
                    <td>{{$article->author_id}}</td>
                    <td>{{$article->privacy_id}}</td>
                    <td>{{$article->comments_enabled}}</td>
                    <td>{{$article->status}}</td>
                    <td>{{$article->template_id}}</td>
                                <td class="text-right">
                                    <a class="btn btn-xs btn-primary" href="{{ route('articles.show', $article->id) }}"><i class="glyphicon glyphicon-eye-open"></i> View</a>
                                    <a class="btn btn-xs btn-warning" href="{{ route('articles.edit', $article->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                                    <form action="{{ route('articles.destroy', $article->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $articles->render() !!}
            @else
                <h3 class="text-center alert alert-info">Empty!</h3>
            @endif

        </div>
    </div>

@endsection