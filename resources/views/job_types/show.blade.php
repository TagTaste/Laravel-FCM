@extends('layout')
@section('header')
    <div class="page-header">
        <h1>JobType / Show #{{$job_type->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('job_types.index') }}"><i
                            class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                <a class="btn btn-sm btn-warning pull-right" href="{{ route('job_types.edit', $job_type->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Name</label>
            <p>
                {{ $job_type->name }}
            </p> <label>Description</label>
            <p>
                {{ $job_type->description }}
            </p>

        </div>

    </div>
@endsection
