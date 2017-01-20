@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-plus"></i> ProjectMembers / Create </h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('project_members.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('project_id')) has-error @endif">
                       <label for="project_id-field">Project_id</label>
                    <input type="text" id="project_id-field" name="project_id" class="form-control" value="{{ old("project_id") }}"/>
                       @if($errors->has("project_id"))
                        <span class="help-block">{{ $errors->first("project_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('project_id')) has-error @endif">
                       <label for="project_id-field">Project_id</label>
                    <input type="text" id="project_id-field" name="project_id" class="form-control" value="{{ old("project_id") }}"/>
                       @if($errors->has("project_id"))
                        <span class="help-block">{{ $errors->first("project_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('profile_id')) has-error @endif">
                       <label for="profile_id-field">Profile_id</label>
                    <input type="text" id="profile_id-field" name="profile_id" class="form-control" value="{{ old("profile_id") }}"/>
                       @if($errors->has("profile_id"))
                        <span class="help-block">{{ $errors->first("profile_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('profile_id')) has-error @endif">
                       <label for="profile_id-field">Profile_id</label>
                    <input type="text" id="profile_id-field" name="profile_id" class="form-control" value="{{ old("profile_id") }}"/>
                       @if($errors->has("profile_id"))
                        <span class="help-block">{{ $errors->first("profile_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('name')) has-error @endif">
                       <label for="name-field">Name</label>
                    <input type="text" id="name-field" name="name" class="form-control" value="{{ old("name") }}"/>
                       @if($errors->has("name"))
                        <span class="help-block">{{ $errors->first("name") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('designation')) has-error @endif">
                       <label for="designation-field">Designation</label>
                    <input type="text" id="designation-field" name="designation" class="form-control" value="{{ old("designation") }}"/>
                       @if($errors->has("designation"))
                        <span class="help-block">{{ $errors->first("designation") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('description')) has-error @endif">
                       <label for="description-field">Description</label>
                    <input type="text" id="description-field" name="description" class="form-control" value="{{ old("description") }}"/>
                       @if($errors->has("description"))
                        <span class="help-block">{{ $errors->first("description") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a class="btn btn-link pull-right" href="{{ route('project_members.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
                </div>
            </form>

        </div>
    </div>
@endsection
@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script>
  <script>
    $('.date-picker').datepicker({
    });
  </script>
@endsection
