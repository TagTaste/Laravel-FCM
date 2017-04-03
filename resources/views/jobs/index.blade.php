@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> Job
            <a class="btn btn-success pull-right" href="{{ route('jobs.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($jobs->count())
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Title</th> <th>Description</th> <th>Type</th> <th>Location</th> <th>Annual_salary</th> <th>Functional_area</th> <th>Key_skills</th> <th>Xpected_role</th> <th>Experience_required</th> <th>Company_id</th>
                            <th class="text-right">OPTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($jobs as $job)
                            <tr>
                                <td class="text-center"><strong>{{$job->id}}</strong></td>

                                <td>{{$job->title}}</td> <td>{{$job->description}}</td> <td>{{$job->type}}</td> <td>{{$job->location}}</td> <td>{{$job->annual_salary}}</td> <td>{{$job->functional_area}}</td> <td>{{$job->key_skills}}</td> <td>{{$job->xpected_role}}</td> <td>{{$job->experience_required}}</td> <td>{{$job->company_id}}</td>
                                
                                <td class="text-right">
                                    <a class="btn btn-xs btn-primary" href="{{ route('jobs.show', $job->id) }}">
                                        <i class="glyphicon glyphicon-eye-open"></i> View
                                    </a>
                                    
                                    <a class="btn btn-xs btn-warning" href="{{ route('jobs.edit', $job->id) }}">
                                        <i class="glyphicon glyphicon-edit"></i> Edit
                                    </a>

                                    <form action="{{ route('jobs.destroy', $job->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete? Are you sure?');">
                                        {{csrf_field()}}
                                        <input type="hidden" name="_method" value="DELETE">

                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $jobs->render() !!}
            @else
                <h3 class="text-center alert alert-info">Empty!</h3>
            @endif

        </div>
    </div>

@endsection