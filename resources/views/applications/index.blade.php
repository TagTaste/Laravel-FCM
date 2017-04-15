@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> Application
            <a class="btn btn-success pull-right" href="{{ route('applications.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($applications->count())
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Job_id</th> <th>Profile_id</th>
                            <th class="text-right">OPTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($applications as $application)
                            <tr>
                                <td class="text-center"><strong>{{$application->id}}</strong></td>

                                <td>{{$application->job_id}}</td> <td>{{$application->profile_id}}</td>
                                
                                <td class="text-right">
                                    <a class="btn btn-xs btn-primary" href="{{ route('applications.show', $application->id) }}">
                                        <i class="glyphicon glyphicon-eye-open"></i> View
                                    </a>
                                    
                                    <a class="btn btn-xs btn-warning" href="{{ route('applications.edit', $application->id) }}">
                                        <i class="glyphicon glyphicon-edit"></i> Edit
                                    </a>

                                    <form action="{{ route('applications.destroy', $application->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete? Are you sure?');">
                                        {{csrf_field()}}
                                        <input type="hidden" name="_method" value="DELETE">

                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $applications->render() !!}
            @else
                <h3 class="text-center alert alert-info">Empty!</h3>
            @endif

        </div>
    </div>

@endsection