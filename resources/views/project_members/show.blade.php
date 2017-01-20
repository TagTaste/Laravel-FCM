@extends('layout')
@section('header')
<div class="page-header">
        <h1>ProjectMembers / Show #{{$project_member->id}}</h1>
        <form action="{{ route('project_members.destroy', $project_member->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('project_members.edit', $project_member->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
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
                     <label for="project_id">PROJECT_ID</label>
                     <p class="form-control-static">{{$project_member->project_id}}</p>
                </div>
                    <div class="form-group">
                     <label for="project_id">PROJECT_ID</label>
                     <p class="form-control-static">{{$project_member->project_id}}</p>
                </div>
                    <div class="form-group">
                     <label for="profile_id">PROFILE_ID</label>
                     <p class="form-control-static">{{$project_member->profile_id}}</p>
                </div>
                    <div class="form-group">
                     <label for="profile_id">PROFILE_ID</label>
                     <p class="form-control-static">{{$project_member->profile_id}}</p>
                </div>
                    <div class="form-group">
                     <label for="name">NAME</label>
                     <p class="form-control-static">{{$project_member->name}}</p>
                </div>
                    <div class="form-group">
                     <label for="designation">DESIGNATION</label>
                     <p class="form-control-static">{{$project_member->designation}}</p>
                </div>
                    <div class="form-group">
                     <label for="description">DESCRIPTION</label>
                     <p class="form-control-static">{{$project_member->description}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('project_members.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection