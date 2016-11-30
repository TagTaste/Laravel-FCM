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
                            @if(!isset($role))
                              {!! Form::open(array('url' => '/admin/role/store', 'method'=>'post', 'id'=>'addRole')) !!}
                            @else
                              {!! Form::open(array('url' => '/admin/role/update/'.$role->id, 'method'=>'post', 'id'=>'editRole')) !!}
                            @endif
                                <div class="form-group">
                                	{!! Form::label('role_name', 'Role Name') !!}
                                	{!! Form::text('role_name', (isset($role)?$role->display_name:"") ,['class'=>'form-control required', 'placeholder'=>'Enter Role Name', 'maxlength'=>'20', 'name'=>'role_name']) !!}
                                    <span class="glyphicon form-control-feedback "></span>
                                    <small class="help-block"></small>
                                </div>
                                <div class="form-group">
                                	{!! Form::label('role_description', 'Role Description') !!}
                                	{!! Form::text('role_description', (isset($role)?$role->description:"") ,['class'=>'form-control required', 'placeholder'=>'Enter Role Description', 'maxlength'=>'50', 'name'=>'role_description']) !!}
                                    <span class="glyphicon form-control-feedback "></span>
                                    <small class="help-block"></small>
                                </div>
                                @if(isset($permissions))
                                    <div class="form-group">
                                        {!! Form::label('role_permissions', 'Select Permissions') !!}
                                        <div class="row">
                                            @foreach($permissions as $key=>$permission)
                                                @if(isset($role_permission) &&in_array($permission->id, $role_permission))
                                                    <div class="col-sm-4">
                                                        <input type="checkbox" checked="checked" name="role_permission[]" value="{{$permission->id}}"> {{$permission->display_name}}
                                                    </div>
                                                @else
                                                    <div class="col-sm-4">
                                                        <input type="checkbox" name="role_permission[]" value="{{$permission->id}}"> {{$permission->display_name}}
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
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