@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> ProjectMembers
            <a class="btn btn-success pull-right" href="{{ route('project_members.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($project_members->count())
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>PROJECT_ID</th>
                        <th>PROJECT_ID</th>
                        <th>PROFILE_ID</th>
                        <th>PROFILE_ID</th>
                        <th>NAME</th>
                        <th>DESIGNATION</th>
                        <th>DESCRIPTION</th>
                            <th class="text-right">OPTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($project_members as $project_member)
                            <tr>
                                <td>{{$project_member->id}}</td>
                                <td>{{$project_member->project_id}}</td>
                    <td>{{$project_member->project_id}}</td>
                    <td>{{$project_member->profile_id}}</td>
                    <td>{{$project_member->profile_id}}</td>
                    <td>{{$project_member->name}}</td>
                    <td>{{$project_member->designation}}</td>
                    <td>{{$project_member->description}}</td>
                                <td class="text-right">
                                    <a class="btn btn-xs btn-primary" href="{{ route('project_members.show', $project_member->id) }}"><i class="glyphicon glyphicon-eye-open"></i> View</a>
                                    <a class="btn btn-xs btn-warning" href="{{ route('project_members.edit', $project_member->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                                    <form action="{{ route('project_members.destroy', $project_member->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $project_members->render() !!}
            @else
                <h3 class="text-center alert alert-info">Empty!</h3>
            @endif

        </div>
    </div>

@endsection