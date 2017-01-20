@extends('layout')
@section('header')
<div class="page-header">
        <h1>Experiences / Show #{{$experience->id}}</h1>
        <form action="{{ route('experiences.destroy', $experience->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('experiences.edit', $experience->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
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
                     <label for="company">COMPANY</label>
                     <p class="form-control-static">{{$experience->company}}</p>
                </div>
                    <div class="form-group">
                     <label for="designation">DESIGNATION</label>
                     <p class="form-control-static">{{$experience->designation}}</p>
                </div>
                    <div class="form-group">
                     <label for="description">DESCRIPTION</label>
                     <p class="form-control-static">{{$experience->description}}</p>
                </div>
                    <div class="form-group">
                     <label for="location">LOCATION</label>
                     <p class="form-control-static">{{$experience->location}}</p>
                </div>
                    <div class="form-group">
                     <label for="start_end">START_END</label>
                     <p class="form-control-static">{{$experience->start_end}}</p>
                </div>
                    <div class="form-group">
                     <label for="end_date">END_DATE</label>
                     <p class="form-control-static">{{$experience->end_date}}</p>
                </div>
                    <div class="form-group">
                     <label for="current_company">CURRENT_COMPANY</label>
                     <p class="form-control-static">{{$experience->current_company}}</p>
                </div>
                    <div class="form-group">
                     <label for="profile_id">PROFILE_ID</label>
                     <p class="form-control-static">{{$experience->profile_id}}</p>
                </div>
                    <div class="form-group">
                     <label for="profile_id">PROFILE_ID</label>
                     <p class="form-control-static">{{$experience->profile_id}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('experiences.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection