@extends('layout')
@section('header')
<div class="page-header">
        <h1>Profiles / Show </h1>
       
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @foreach($profile as $id=>$attributes)
                <div class="col-md-12">
                
                    <h3>{{ $attributes->first()->attribute->label}}</h3>

                    @foreach($attributes as $attribute)
                        {{ $attribute->getValue() }}<br/>
                    @endforeach
                </div>
            @endforeach
            {{-- <form action="#">
                <div class="form-group">
                    <label for="nome">ID</label>
                    <p class="form-control-static"></p>
                </div>
                <div class="form-group">
                     <label for="user_id">USER_ID</label>
                     <p class="form-control-static">{{$profile->user_id}}</p>
                </div>
                    <div class="form-group">
                     <label for="attribute_id">ATTRIBUTE_ID</label>
                     <p class="form-control-static">{{$profile->attribute_id}}</p>
                </div>
                    <div class="form-group">
                     <label for="value">VALUE</label>
                     <p class="form-control-static">{{$profile->value}}</p>
                </div>
                    <div class="form-group">
                     <label for="type_id">TYPE_ID</label>
                     <p class="form-control-static">{{$profile->type_id}}</p>
                </div>
            </form> --}}

            <a class="btn btn-link" href="{{ route('profiles.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection