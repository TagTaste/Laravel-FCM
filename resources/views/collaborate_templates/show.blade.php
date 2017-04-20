@extends('layout')
@section('header')
    <div class="page-header">
        <h1>CollaborateTemplate / Show #{{$collaborate_template->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('collaborate_templates.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('collaborate_templates.edit', $collaborate_template->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Name</label>
<p>
	{{ $collaborate_template->name }}
</p> <label>Fields</label>
<p>
	{{ $collaborate_template->fields }}
</p>

        </div>

    </div>
@endsection
