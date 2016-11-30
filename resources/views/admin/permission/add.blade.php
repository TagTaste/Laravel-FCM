@extends('admin_template.layout')

@section('content')
<div class="wrapper wrapper-content animated">
    <div class="row">
    	<div class="col-lg-3"></div>
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h4>{{$pageTitle}}</h4>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-sm-12">
                            @if(!isset($permission))
                              {!! Form::open(array('url' => '/admin/permission/store', 'method'=>'post', 'id'=>'addPermission')) !!}
                            @else
                              {!! Form::open(array('url' => '/admin/permission/update/'.$permission->id, 'method'=>'post', 'id'=>'editPermission')) !!}
                            @endif
                                <div class="form-group">
                                	{!! Form::label('permission_name', 'Permission Name') !!}
                                	{!! Form::text('permission_name', (isset($permission)?$permission->display_name:"") ,['class'=>'form-control required', 'placeholder'=>'Enter Permission Name', 'maxlength'=>'20', 'name'=>'permission_name']) !!}
                                    <span class="glyphicon form-control-feedback "></span>
                                    <small class="help-block"></small>
                                </div>
                                <div class="form-group">
                                	{!! Form::label('permission_description', 'Permission Description') !!}
                                	{!! Form::text('permission_description', (isset($permission)?$permission->description:"") ,['class'=>'form-control required', 'placeholder'=>'Enter Permission Description', 'maxlength'=>'50', 'name'=>'permission_description']) !!}
                                    <span class="glyphicon form-control-feedback "></span>
                                    <small class="help-block"></small>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong>{{$buttonLabel}}</strong></button>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
       	<div class="col-lg-3"></div>
    </div>
</div>
@endsection