@extends('layout')
@section('header')
    <div class="page-header">
        <h1>Application / Show #{{$application->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('applications.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('applications.edit', $application->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Job_id</label>
<p>
	{{ $application->job_id }}
</p> <label>Profile_id</label>
<p>
	{{ $application->profile_id }}
</p>

        </div>

    </div>
@endsection
