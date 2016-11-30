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
            <div class="panel panel-default" style="position: relative;">
             
             <div style="width: 100%;height: 250px;background-image:url('https://img.werecipes.com/wp/wp-content/uploads/2015/02/restuarant-style-veg-manchow-soup-recipe.jpg');">
                    
                </div>
                <p style="position:absolute;top:1em;left:1em;">
                    <span style="color:red;font-size:2em;" class="glyphicon glyphicon-fire" aria-hidden="true"></span>
                </p>
                <p class="text-right" style="position: absolute;top: 1em;right: 1em;">
                            <a title="Like" class="btn btn-default" href="#">
                                <span style="color:red;margin-right:0.5em;" class="glyphicon glyphicon-heart" aria-hidden="true"></span>43</a>
                            <a title="Favourite" class="btn btn-default" href="#">
                                <span style="margin-right:0.5em;" class="glyphicon glyphicon-star" aria-hidden="true"></span> 99
                            </a>
                            <a title="Subscribe" class="btn btn-default" href="#">
                                <span style="margin-right:0.5em;" class="glyphicon glyphicon-plus" aria-hidden="true"></span>305
                            </a>

                        </p>

             <div class="panel-body">
                
                <div>
                    <div class="col-md-12">
                        <h3>{{ $article->title }}</h3>
                    </div>

                    <div class="col-md-9">
                        {{-- <!-- <h6 class="subtitle text-italic"><em>By: {{ $article->getAuthor() }}</em> <a href="{{route('chef.follow',$article->author->user_id)}}">Follow</a></h6>
 --> --}}
                        <div style="" class="col-md-2"><img src="http://placehold.it/50x50" alt=""></div>
                        <div class="col-md-8"> <em>{{ $article->getAuthor() }}</em> <br/><a href="{{route('chef.follow',$article->author->user_id)}}" class="btn btn-xs btn-default" style="margin:0.5em;">Follow</a> </div>


                    </div>

                    <div class="col-md-3">
                        <p class="text-right text-muted">{{ $article->created_at->toFormattedDateString() }}</p>

                    </div>

                    <div style="padding:0px 2em 2em 2em;" class="col-md-12 text-justify">
                        <hr>
                        <p>{{ $article->getContent() }}</p>
                        @if($article->hasRecipe())
                        <p style="margin-top:2em;"><a class="btn btn-default" href="#">View Recipe</a></p>
                    
                        @endif
                    </div>
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
