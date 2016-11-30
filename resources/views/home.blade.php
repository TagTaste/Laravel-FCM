@extends('layout')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">Trending</div>
                <div class="panel-body">content</div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Messages</div>
                <div class="panel-body">content</div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Events</div>
                <div class="panel-body">content</div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Groups</div>
                <div class="panel-body">content</div>
            </div>
        </div>
        <div class="col-md-6">    
            @foreach($articles as $article)
            <div class="panel panel-default">
             <div class="panel-heading">

             </div>
             <div class="panel-body">
                <h4>{{ $article->title }}</h4>

                <div class="col-md-9">
                    <h6 class="subtitle">By: {{ $article->getAuthor() }} <a href="{{route('chef.follow',$article->author->user_id)}}">Follow</a></h6>
                </div>

                <div class="col-md-3">
                    <p class="text-right text-muted">{{ $article->created_at->toFormattedDateString() }}</p>

                </div>

                <div class="col-md-12 text-justify">
                    <hr>
                    <p>{{ $article->getContent() }}</p>
                    @if($article->hasRecipe())
                    <p><a href="#">View Recipe</a></p>
                    <p class='text-right'>
                        <a title="Like" class="btn btn-default" href="#"><span class="glyphicon glyphicon-heart" aria-hidden="true"></span></a>
                        <a title="Favourite" class="btn btn-default" href="#">
                            <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                        </a>
                        <a title="Subscribe" class="btn btn-default" href="#">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        </a>

                    </p>
                    @endif
                </div>
            </div>

        </div> 
        @endforeach


    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">People to Follow</div>

            <div class="panel-body">
                <ul>
                    @foreach($chefs as $chef)
                    <li> 
                        <p> 
                            <img src="http://placehold.it/50x50" alt="">
                            {{ $chef->name }} <a href="{{ route('chef.follow', $chef->id) }}">Follow</a>    
                        </p>
                    </li>
                    @endforeach
                </ul>

            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Chefs Followed</div>

            <div class="panel-body">
                <ul class='list-unstyled'>
                    @foreach($chefsFollowed as $chef)
                    <li class="col-md-12"> 
                        <div style="margin-right:1em;" class="col-md-3"><img src="http://placehold.it/50x50" alt=""></div>
                        <div class="col-md-8"> {{ $chef->chef->name }} <br/><a href="{{ route('chef.unfollow', $chef->chef->id) }}">Unfollow</a> </div>



                    </li>
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

        <div class="panel panel-default">
            <div class="panel-heading">Who Viewed You</div>

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
