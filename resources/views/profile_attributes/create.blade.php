@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-plus"></i> Profile Attributes / Create </h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('profile_attributes.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('profile_type_id')) has-error @endif""> 
                    <label for="profile_type_id">Profile Type</label>
                    <select id="profile_type_id" name="profile_type_id" class="form-control">
                      @foreach($profileTypes as $id => $type)
                        <option value="{{ $id }}" @if(old("profile_type_id")) selected @endif>{{ $type }} </option>
                      @endforeach
                    </select>
                </div>

                <div class="form-group @if($errors->has('name')) has-error @endif">
                       <label for="name-field">Name</label>
                    <input type="text" id="name-field" name="name" class="form-control" value="{{ old("name") }}"/>
                       @if($errors->has("name"))
                        <span class="help-block">{{ $errors->first("name") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('label')) has-error @endif">
                       <label for="label-field">Label</label>
                    <input type="text" id="label-field" name="label" class="form-control" value="{{ old("label") }}"/>
                       @if($errors->has("label"))
                        <span class="help-block">{{ $errors->first("label") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('description')) has-error @endif">
                       <label for="description-field">Description</label>
                    <input type="text" id="description-field" name="description" class="form-control" value="{{ old("description") }}"/>
                       @if($errors->has("description"))
                        <span class="help-block">{{ $errors->first("description") }}</span>
                       @endif
                    </div>
                    <!-- <div class="form-group @if($errors->has('user_id')) has-error @endif">
                       <label for="user_id-field">User_id</label>
                    <input type="text" id="user_id-field" name="user_id" class="form-control" value="{{ old("user_id") }}"/>
                       @if($errors->has("user_id"))
                        <span class="help-block">{{ $errors->first("user_id") }}</span>
                       @endif
                    </div> -->
                    <div class="form-group @if($errors->has('multiline')) has-error @endif">
                       <label for="multiline">Multiline</label>
                    <div class="btn-group" data-toggle="buttons"><label class="btn btn-primary"><input type="radio" value="1" name="multiline" id="multiline" autocomplete="off"> Yes </label><label class="btn btn-primary active"><input type="radio" name="multiline" value="0" id="multiline" autocomplete="off"> No </label></div>
                       @if($errors->has("multiline"))
                        <span class="help-block">{{ $errors->first("multiline") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('requires_upload')) has-error @endif">
                       <label for="requires_upload">Requires Upload</label>
                    <div class="btn-group" data-toggle="buttons"><label class="btn btn-primary"><input type="radio" value="1" name="requires_upload" id="requires_upload" autocomplete="off"> Yes </label><label class="btn btn-primary active"><input type="radio" name="requires_upload" value="0" id="requires_upload" autocomplete="off"> No </label></div>
                       @if($errors->has("requires_upload"))
                        <span class="help-block">{{ $errors->first("requires_upload") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('allowed_mime_types')) has-error @endif">
                       <label for="allowed_mime_types">Allowed Mime Types</label>
                    <input type="text" id="allowed_mime_types" name="allowed_mime_types" class="form-control" value="{{ old("allowed_mime_types") }}"/>
                       @if($errors->has("allowed_mime_types"))
                        <span class="help-block">{{ $errors->first("allowed_mime_types") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('enabled')) has-error @endif">
                       <label for="enabled">Enabled</label>
                    <div class="btn-group" data-toggle="buttons"><label class="btn btn-primary"><input type="radio" value="1" name="enabled" id="enabled" autocomplete="off"> Yes </label><label class="btn btn-primary active"><input type="radio" name="enabled" value="0" id="enabled" autocomplete="off"> No </label></div>
                       @if($errors->has("enabled"))
                        <span class="help-block">{{ $errors->first("enabled") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('required')) has-error @endif">
                       <label for="required">Required</label>
                    <div class="btn-group" data-toggle="buttons"><label class="btn btn-primary"><input type="radio" value="1" name="required" id="required" autocomplete="off"> Yes </label><label class="btn btn-primary active"><input type="radio" name="required" value="0" id="required" autocomplete="off"> No </label></div>
                       @if($errors->has("required"))
                        <span class="help-block">{{ $errors->first("required") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('parent_id')) has-error @endif">
                       <label for="parent_id-field">Parent</label>
                    <input type="text" id="parent_id-field" name="parent_id" class="form-control" value="{{ old("parent_id") }}"/>
                       @if($errors->has("parent_id"))
                        <span class="help-block">{{ $errors->first("parent_id") }}</span>
                       @endif
                    </div>
  
                    
                    <!-- <div class="form-group @if($errors->has('template_id')) has-error @endif">
                       <label for="template_id-field">Template_id</label>
                    <input type="text" id="template_id-field" name="template_id" class="form-control" value="{{ old("template_id") }}"/>
                       @if($errors->has("template_id"))
                        <span class="help-block">{{ $errors->first("template_id") }}</span>
                       @endif
                    </div> -->
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a class="btn btn-link pull-right" href="{{ route('profile_attributes.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
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
