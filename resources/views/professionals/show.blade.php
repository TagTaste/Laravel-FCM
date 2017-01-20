@extends('layout')
@section('header')
<div class="page-header">
        <h1>Professionals / Show #{{$professional->id}}</h1>
        <form action="{{ route('professionals.destroy', $professional->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('professionals.edit', $professional->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
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
                     <label for="ingredients">INGREDIENTS</label>
                     <p class="form-control-static">{{$professional->ingredients}}</p>
                </div>
                    <div class="form-group">
                     <label for="favourite_moments">FAVOURITE_MOMENTS</label>
                     <p class="form-control-static">{{$professional->favourite_moments}}</p>
                </div>
                    <div class="form-group">
                     <label for="profile_id">PROFILE_ID</label>
                     <p class="form-control-static">{{$professional->profile_id}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('professionals.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection