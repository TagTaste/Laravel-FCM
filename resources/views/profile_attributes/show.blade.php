@extends('layout')
@section('header')
<div class="page-header">
        <h1>ProfileAttributes / Show #{{$profileAttribute->id}}</h1>
        <form action="{{ route('profile_attributes.destroy', $profileAttribute->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('profile_attributes.edit', $profileAttribute->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                <button type="submit" class="btn btn-danger">Delete <i class="glyphicon glyphicon-trash"></i></button>
            </div>
        </form>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            @include('profile_attributes.single',compact('$profileAttribute'))

            <a class="btn btn-link" href="{{ route('profile_attributes.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection