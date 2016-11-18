@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> ProfileAttributes / Edit #{{$profile_attribute->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('profile_attributes.update', $profile_attribute->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('name')) has-error @endif">
                       <label for="name-field">Name</label>
                    <input type="text" id="name-field" name="name" class="form-control" value="{{ is_null(old("name")) ? $profile_attribute->name : old("name") }}"/>
                       @if($errors->has("name"))
                        <span class="help-block">{{ $errors->first("name") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('label')) has-error @endif">
                       <label for="label-field">Label</label>
                    <input type="text" id="label-field" name="label" class="form-control" value="{{ is_null(old("label")) ? $profile_attribute->label : old("label") }}"/>
                       @if($errors->has("label"))
                        <span class="help-block">{{ $errors->first("label") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('description')) has-error @endif">
                       <label for="description-field">Description</label>
                    <input type="text" id="description-field" name="description" class="form-control" value="{{ is_null(old("description")) ? $profile_attribute->description : old("description") }}"/>
                       @if($errors->has("description"))
                        <span class="help-block">{{ $errors->first("description") }}</span>
                       @endif
                    </div>
                    <!-- <div class="form-group @if($errors->has('user_id')) has-error @endif">
                       <label for="user_id-field">User_id</label>
                    <input type="text" id="user_id-field" name="user_id" class="form-control" value="{{ is_null(old("user_id")) ? $profile_attribute->user_id : old("user_id") }}"/>
                       @if($errors->has("user_id"))
                        <span class="help-block">{{ $errors->first("user_id") }}</span>
                       @endif
                    </div> -->
                    <div class="form-group @if($errors->has('multiline')) has-error @endif">
                       <label for="multiline">Multiline</label>
                    <div class="btn-group" data-toggle="buttons"><label class="btn btn-primary"><input type="radio" value="true" name="multiline" id="multiline" autocomplete="off"> True</label><label class="btn btn-primary active"><input type="radio" name="multiline" value="false" id="multiline" autocomplete="off"> False</label></div>
                       @if($errors->has("multiline"))
                        <span class="help-block">{{ $errors->first("multiline") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('requires_upload')) has-error @endif">
                       <label for="requies_upload">Requires Upload</label>
                    <div class="btn-group" data-toggle="buttons"><label class="btn btn-primary"><input type="radio" value="true" name="requies_upload" id="requies_upload" autocomplete="off"> True</label><label class="btn btn-primary active"><input type="radio" name="requies_upload" value="false" id="requies_upload" autocomplete="off"> False</label></div>
                       @if($errors->has("requires_upload"))
                        <span class="help-block">{{ $errors->first("requires_upload") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('allowed_mime_types')) has-error @endif">
                       <label for="allowed_mime_types-field">Allowed Mime Types</label>
                    <input type="text" id="allowed_mime_types-field" name="allowed_mime_types" class="form-control" value="{{ is_null(old("allowed_mime_types")) ? $profile_attribute->allowed_mime_types : old("allowed_mime_types") }}"/>
                       @if($errors->has("allowed_mime_types"))
                        <span class="help-block">{{ $errors->first("allowed_mime_types") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('enabled')) has-error @endif">
                       <label for="enabled">Enabled</label>
                    <div class="btn-group" data-toggle="buttons"><label class="btn btn-primary"><input type="radio" value="true" name="enabled" id="enabled" autocomplete="off"> True</label><label class="btn btn-primary active"><input type="radio" name="enabled" value="false" id="enabled" autocomplete="off"> False</label></div>
                       @if($errors->has("enabled"))
                        <span class="help-block">{{ $errors->first("enabled") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('required')) has-error @endif">
                       <label for="required">Required</label>
                    <div class="btn-group" data-toggle="buttons"><label class="btn btn-primary"><input type="radio" value="true" name="required" id="required" autocomplete="off"> True</label><label class="btn btn-primary active"><input type="radio" name="required" value="false" id="required" autocomplete="off"> False</label></div>
                       @if($errors->has("required"))
                        <span class="help-block">{{ $errors->first("required") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('parent_id')) has-error @endif">
                       <label for="parent_id-field">Parent_id</label>
                    <input type="text" id="parent_id-field" name="parent_id" class="form-control" value="{{ is_null(old("parent_id")) ? $profile_attribute->parent_id : old("parent_id") }}"/>
                       @if($errors->has("parent_id"))
                        <span class="help-block">{{ $errors->first("parent_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('profile_type_id')) has-error @endif">
                       <label for="profile_type_id-field">profile_type_id</label>
                    <input type="text" id="profile_type_id-field" name="profile_type_id" class="form-control" value="{{ is_null(old("profile_type_id")) ? $profile_attribute->profile_type_id : old("profile_type_id") }}"/>
                       @if($errors->has("profile_type_id"))
                        <span class="help-block">{{ $errors->first("profile_type_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('template_id')) has-error @endif">
                       <label for="template_id-field">Template_id</label>
                    <input type="text" id="template_id-field" name="template_id" class="form-control" value="{{ is_null(old("template_id")) ? $profile_attribute->template_id : old("template_id") }}"/>
                       @if($errors->has("template_id"))
                        <span class="help-block">{{ $errors->first("template_id") }}</span>
                       @endif
                    </div>

                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('profile_attributes.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
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
