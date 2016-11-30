    @extends('layout')

    @section('header')
  <!--   <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> My Profiles
            <a class="btn btn-success pull-right hide" href="{{ route('profiles.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>

    </div> -->
    @endsection

    @section('content')
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">Your Profiles</div>

            <div class="panel-body">
                <div class="col-md-12">
                    @if($profiles->count())

                    <div>
                     <ul class="nav nav-tabs" role="tablist">
                         @php
                         $first = true;

                         @endphp
                         @foreach($profileTypes as $profileType)
                         <!-- Nav tabs -->

                         <li role="presentation" class="@if($first) active @php $first=false;@endphp @endif"><a href="#{{$profileType->type}}" aria-controls="{{$profileType->type}}" role="tab" data-toggle="tab">{{ $profileType->type }}</a></li>

                         @endforeach
                     </ul>

                     @php
                     $first = true;

                     @endphp
                     <!-- Tab panes -->

                     <div class="tab-content">
                        @foreach($profileTypes as $profileType)
                        <div role="tabpanel" class="@if($first) active @php $first=false;@endphp @endif tab-pane" id="{{ $profileType->type }}">
                            <p class="text-right">
                            <a style="margin:1em;" class="btn btn-default" href="{{ route('profiles.edit',$profileType->id) }} ">Update {{$profileType->type}} Profile</a>
                            </p>
                            @php
                            $prof = $profiles->get($profileType->id);

                            @endphp
                            @if($prof && $prof->count())
                            <ul>
                                @foreach($prof->groupBy('profile_attribute_id') as $p)
                                @php
                                $label = $p->first()->attribute->label;


                                @endphp
                                <li>
                                    <h5>{{$label}}</h5>
                                    @foreach($p as $value)
                                    <p> {{ $value->getValue()}} </p>

                                    @endforeach
                                </li>


                                @endforeach
                            </ul>
                            @endif 

                        </div>
                        @endforeach

                    </div>

                </div>
            </div>
        </div>
        
        @endif

        

    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">My Interests</div>

                <div class="panel-body">
                    <ul>
                        <li>Chinese Cuisine</li>
                        <li>Quick Recipes</li>
                        <li>Innovative Cooking</li>
                    </ul>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Your Articles</div>

                <div class="panel-body">
                    <ul class="list-unstyled">
                        <li  class="col-md-12">
                            <h3>Manchow Soup In 10 Easy Steps</h3>
                            <p class="col-md-3">
                                <img style="padding:1em;" height="120px" width="120px" src="https://img.werecipes.com/wp/wp-content/uploads/2015/02/restuarant-style-veg-manchow-soup-recipe.jpg" alt="">
                            </p>
                            <p class="col-md-9">

                                <p style="padding:1em;">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eaque voluptatum odit, inventore eius repudiandae. Quo magnam, fugit, repudiandae odio facilis at labore mollitia, placeat soluta dolorum quibusdam necessitatibus aperiam possimus.</p>
                            </p>
                            <p>
                                <a class="btn btn-default" href="#">Read Article</a>
                            </p>
                            <hr>
                        </li>

                        <li  class="col-md-12">
                            <h3>Manchow Soup In 10 Easy Steps</h3>
                            <p class="col-md-3">
                                <img style="padding:1em;" height="120px" width="120px" src="https://img.werecipes.com/wp/wp-content/uploads/2015/02/restuarant-style-veg-manchow-soup-recipe.jpg" alt="">
                            </p>
                            <p class="col-md-9">
                                
                                <p style="padding:1em;">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eaque voluptatum odit, inventore eius repudiandae. Quo magnam, fugit, repudiandae odio facilis at labore mollitia, placeat soluta dolorum quibusdam necessitatibus aperiam possimus.</p>
                            </p>
                            <p>
                                <a class="btn btn-default" href="#">Read Article</a>
                            </p>
                            <hr>
                        </li>

                        <li  class="col-md-12">
                            <h3>Manchow Soup In 10 Easy Steps</h3>
                            <p class="col-md-3">
                                <img style="padding:1em;" height="120px" width="120px" src="https://img.werecipes.com/wp/wp-content/uploads/2015/02/restuarant-style-veg-manchow-soup-recipe.jpg" alt="">
                            </p>
                            <p class="col-md-9">
                                
                                <p style="padding:1em;">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eaque voluptatum odit, inventore eius repudiandae. Quo magnam, fugit, repudiandae odio facilis at labore mollitia, placeat soluta dolorum quibusdam necessitatibus aperiam possimus.</p>
                            </p>
                            <p>
                                <a class="btn btn-default" href="#">Read Article</a>
                            </p>
                            <hr>
                        </li>
                        
                    </ul>

                </div>
            </div>
        </div>
    </div>


    <div class="col-md-6 hide">
        <h3> View attributes for: </h3>
        <ul>
            @foreach($profileTypes as $type)
            <li><a href="{{ route('profile.form',$type->id)}}">{{$type->type}}</a></li>
            @endforeach
        </ul>
    </div>

    <div class="col-md-6 hide">
        <h3>View Profile</h3>
        <ul>
            @foreach($profileTypes as $type)
            <li><a href="{{ route('profiles.show',$type->id)}}"> {{$type->type}} </a></li>
            @endforeach
        </ul>
    </div>
</div>

@endsection