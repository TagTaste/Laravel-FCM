    @extends('layout')

    @section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> Profiles
            <a class="btn btn-success pull-right hide" href="{{ route('profiles.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>

    </div>
    @endsection

    @section('content')
    <div class="row">
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
                        <a href="{{ route('profiles.edit',$profileType->id) }} ">Edit Profile</a>
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
        @endif

        

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