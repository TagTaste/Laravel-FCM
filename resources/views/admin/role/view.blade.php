@extends('admin_template.layout')

@section('content')
<div class="wrapper wrapper-content animated">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h4>{{$pageTitle}}</h4>
                </div>
                <div class="ibox-content">
                	<div class="table-responsive">
                    	<table class="table table-striped table-bordered table-hover" id="rolesTable" >
                    		<thead>
			                    <tr>
			                        <th>S. No.</th>
			                        <th>Name</th>
			                        <th>Description</th>
			                        <th>Permissions</th>
			                        <th style="text-align: center;">Action</th>
			                    </tr>
			                </thead>
			           		<tbody>
			           			@foreach($roles as $key=>$role)
	                    			<tr>
	                        			<td>{{++$key}}</td>
	                        			<td>{{$role['display_name']}}</td>
				                        <td>{{$role['description']}}</td>
				                        <td>
				                        	@if(isset($role->perms))
				                        		@foreach($role->perms as $perm)
				                        			{{$permission[$perm->pivot->permission_id]}} <br>
				                        		@endforeach
				                        	@endif
				                        </td>
				                        <td style="text-align: center;">
				                        	<button class="editRole" value="{{$role['id']}}"><span class="glyphicon glyphicon-pencil" style="color: black;" data-toggle="tooltip" title="Edit Role"></span></button>
			                        		<button class="deleteRole" value="{{$role['id']}}" data-toggle="tooltip" title="Delete Role"><span class="glyphicon glyphicon-ban-circle" style="color: #E53935;"></span></button>
				                        </td>
				                    </tr>
							    @endforeach
			                </tbody>
			            </table>
			        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection