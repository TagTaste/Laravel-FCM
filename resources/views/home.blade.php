@extends('layout')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">Left Side</div>
                <div class="panel-body">Left Side</div>
            </div>
        </div>
        <div class="col-md-6">
           
          
                
                    @foreach($articles as $article)
                     <div class="panel panel-default">
                           <div class="panel-heading">
                                
                            </div>
                        <div class="panel-body">
                        <h4>{{ $article->title }}</h4>
                                <h6 class="subtitle">By: {{ $article->getAuthor() }} </h6>
                                <hr>
                           <p>{{ $article->getContent() }}</p>
                           @if($article->hasRecipe())
                            <p><a href="#">View Recipe</a></p>
                           @endif
                        </div>

                    </div> 
                    @endforeach
                
           
        </div>
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                        <ul>
                            @foreach($chefs as $chef)
                                <li> {{ $chef->name }} <a href="{{ route('chef.follow', $chef->id) }}">Follow</a> </li>
                            @endforeach
                        </ul>
                    
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Chefs Followed</div>

                <div class="panel-body">
                        <ul>
                            @foreach($chefsFollowed as $chef)
                                <li> {{ $chef->chef->name }} <a href="{{ route('chef.unfollow', $chef->chef->id) }}">Unfollow</a> </li>
                            @endforeach
                        </ul>
                    
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Following You</div>

                <div class="panel-body">
                        <ul>
                            @foreach($followers as $follower)
                                <li> {{ $follower->follower->name }}</li>
                            @endforeach
                        </ul>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
