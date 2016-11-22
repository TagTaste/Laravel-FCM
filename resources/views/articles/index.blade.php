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
            <div class="col-md-9">
                <h2>Dishes</h2>
                @foreach($dishes as $dish)
                    <div class="col-md-12">
                        <h3> {{ $dish->article-> title }} </h3>
                        <p class='text-justify'> {!! $dish->content !!}</p>
                        @if(!is_null($dish->recipe))
                            <h3> Recipe </h3>
                            <ul>
                                
                                    @foreach($dish->recipe as $recipe)
                                        <li>
                                            <h4> Step {{ $recipe->step }} </h4>
                                            <p> {{ $recipe->content }} </p>
                                        </li>
                                    @endforeach

                               
                            </ul>
                         @endif
                    </div>
                    <hr>
                @endforeach
            </div>

            

        </div>
    </div>

@endsection