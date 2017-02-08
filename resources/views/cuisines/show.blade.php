@extends('layout')
@section('header')
<div class="page-header">
        <h1>Cuisines / Show #{{$cuisine->id}}</h1>
        <form action="{{ route('cuisines.destroy', $cuisine->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('cuisines.edit', $cuisine->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
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
                     <label for="name">NAME</label>
                     <p class="form-control-static">{{$cuisine->name}}</p>
                </div>
                    <div class="form-group">
                     <label for="public">PUBLIC</label>
                     <p class="form-control-static">{{$cuisine->public}}</p>
                </div>
                    <div class="form-group">
                     <label for="count">COUNT</label>
                     <p class="form-control-static">{{$cuisine->count}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('cuisines.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection