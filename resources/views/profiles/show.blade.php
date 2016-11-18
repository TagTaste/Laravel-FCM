@extends('layout')
@section('header')
<div class="page-header">
        <h1>Profiles / Show #{{$profile->id}}</h1>
        <form action="{{ route('profiles.destroy', $profile->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('profiles.edit', $profile->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                <button type="submit" class="btn btn-danger">Delete <i class="glyphicon glyphicon-trash"></i></button>
            </div>
        </form>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <form action="#">
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
            </form>

            <a class="btn btn-link" href="{{ route('profiles.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection