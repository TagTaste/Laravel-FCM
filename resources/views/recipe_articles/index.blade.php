@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> RecipeArticles
            <a class="btn btn-success pull-right" href="{{ route('recipe_articles.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($recipe_articles->count())
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>DISH_ID</th>
                        <th>STEP</th>
                        <th>CONTENT</th>
                        <th>TEMPLATE_ID</th>
                        <th>PARENT_ID</th>
                            <th class="text-right">OPTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($recipe_articles as $recipe_article)
                            <tr>
                                <td>{{$recipe_article->id}}</td>
                                <td>{{$recipe_article->dish_id}}</td>
                    <td>{{$recipe_article->step}}</td>
                    <td>{{$recipe_article->content}}</td>
                    <td>{{$recipe_article->template_id}}</td>
                    <td>{{$recipe_article->parent_id}}</td>
                                <td class="text-right">
                                    <a class="btn btn-xs btn-primary" href="{{ route('recipe_articles.show', $recipe_article->id) }}"><i class="glyphicon glyphicon-eye-open"></i> View</a>
                                    <a class="btn btn-xs btn-warning" href="{{ route('recipe_articles.edit', $recipe_article->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                                    <form action="{{ route('recipe_articles.destroy', $recipe_article->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $recipe_articles->render() !!}
            @else
                <h3 class="text-center alert alert-info">Empty!</h3>
            @endif

        </div>
    </div>

@endsection