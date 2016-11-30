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
                    	<table class="table table-striped table-bordered table-hover" id="permissionsTable" >
                    		<thead>
			                    <tr>
			                        <th>S. No.</th>
			                        <th>Name</th>
			                        <th>Description</th>
			                        <th style="text-align: center;">Action</th>
			                    </tr>
			                </thead>
			           		<tbody>
			           			@foreach($permissions as $key=>$permission)
	                    			<tr>
	                        			<td>{{++$key}}</td>
	                        			<td>{{$permission['display_name']}}</td>
				                        <td>{{$permission['description']}}</td>
				                        <td style="text-align: center;">
				                        	<button class="editPermission" value="{{$permission['id']}}"><span class="glyphicon glyphicon-pencil" style="color: black;" data-toggle="tooltip" title="Edit Permission"></span></button>
			                        		<button class="deletePermission" value="{{$permission['id']}}" data-toggle="tooltip" title="Delete Permission"><span class="glyphicon glyphicon-ban-circle" style="color: #E53935;"></span></button>
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