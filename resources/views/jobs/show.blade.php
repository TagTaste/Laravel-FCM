@extends('layout')
@section('header')
    <div class="page-header">
        <h1>Job / Show #{{$job->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('jobs.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('jobs.edit', $job->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Title</label>
<p>
	{{ $job->title }}
</p> <label>Description</label>
<p>
	{{ $job->description }}
</p> <label>Type</label>
<p>
	{{ $job->type }}
</p> <label>Location</label>
<p>
	{{ $job->location }}
</p> <label>Annual_salary</label>
<p>
	{{ $job->annual_salary }}
</p> <label>Functional_area</label>
<p>
	{{ $job->functional_area }}
</p> <label>Key_skills</label>
<p>
	{{ $job->key_skills }}
</p> <label>Expected_role</label>
<p>
    {{ $job->expected_role }}
</p> <label>Experience_required</label>
<p>
	{{ $job->experience_required }}
</p> <label>Company_id</label>
<p>
	{{ $job->company_id }}
</p>

        </div>

    </div>
@endsection
