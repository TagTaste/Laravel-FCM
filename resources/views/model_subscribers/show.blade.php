@extends('layout')
@section('header')
    <div class="page-header">
        <h1>ModelSubscriber / Show #{{$model_subscriber->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('model_subscribers.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('model_subscribers.edit', $model_subscriber->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Model</label>
<p>
	{{ $model_subscriber->model }}
</p> <label>Model_id</label>
<p>
	{{ $model_subscriber->model_id }}
</p> <label>Profile_id</label>
<p>
	{{ $model_subscriber->profile_id }}
</p> <label>Muted_on</label>
<p>
	{{ $model_subscriber->muted_on }}
</p>

        </div>

    </div>
@endsection
