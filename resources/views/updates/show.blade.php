@extends('layout')
@section('header')
    <div class="page-header">
        <h1>Update / Show #{{$update->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('updates.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('updates.edit', $update->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Chema=content</label>
<p>
	{{ $update->chema=content }}
</p> <label>Model_id</label>
<p>
	{{ $update->model_id }}
</p> <label>Model_name</label>
<p>
	{{ $update->model_name }}
</p> <label>Profile_id</label>
<p>
	{{ $update->profile_id }}
</p>

        </div>

    </div>
@endsection
