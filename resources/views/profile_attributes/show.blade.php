@extends('layout')
@section('header')
<div class="page-header">
        <h1>ProfileAttributes / Show #{{$profile_attribute->id}}</h1>
        <form action="{{ route('profile_attributes.destroy', $profile_attribute->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('profile_attributes.edit', $profile_attribute->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
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
                     <p class="form-control-static">{{$profile_attribute->name}}</p>
                </div>
                    <div class="form-group">
                     <label for="label">LABEL</label>
                     <p class="form-control-static">{{$profile_attribute->label}}</p>
                </div>
                    <div class="form-group">
                     <label for="description">DESCRIPTION</label>
                     <p class="form-control-static">{{$profile_attribute->description}}</p>
                </div>
                    <div class="form-group">
                     <label for="user_id">USER_ID</label>
                     <p class="form-control-static">{{$profile_attribute->user_id}}</p>
                </div>
                    <div class="form-group">
                     <label for="multiline">MULTILINE</label>
                     <p class="form-control-static">{{$profile_attribute->multiline}}</p>
                </div>
                    <div class="form-group">
                     <label for="requires_upload">REQUIRES_UPLOAD</label>
                     <p class="form-control-static">{{$profile_attribute->requires_upload}}</p>
                </div>
                    <div class="form-group">
                     <label for="allowed_mime_types">ALLOWED_MIME_TYPES</label>
                     <p class="form-control-static">{{$profile_attribute->allowed_mime_types}}</p>
                </div>
                    <div class="form-group">
                     <label for="enabled">ENABLED</label>
                     <p class="form-control-static">{{$profile_attribute->enabled}}</p>
                </div>
                    <div class="form-group">
                     <label for="required">REQUIRED</label>
                     <p class="form-control-static">{{$profile_attribute->required}}</p>
                </div>
                    <div class="form-group">
                     <label for="parent_id">PARENT_ID</label>
                     <p class="form-control-static">{{$profile_attribute->parent_id}}</p>
                </div>
                    <div class="form-group">
                     <label for="template_id">TEMPLATE_ID</label>
                     <p class="form-control-static">{{$profile_attribute->template_id}}</p>
                </div>
                <div class="form-group">
                     <label for="template_id">PROFILE TYPE</label>
                     <p class="form-control-static">{{$profile_attribute->profileType->type}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('profile_attributes.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection