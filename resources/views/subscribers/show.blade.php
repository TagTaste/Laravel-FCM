@extends('layout')
@section('header')
    <div class="page-header">
        <h1>Subscriber / Show #{{$subscriber->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('subscribers.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('subscribers.edit', $subscriber->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Channel_name</label>
<p>
	{{ $subscriber->channel_name }}
</p> <label>Profile_id</label>
<p>
	{{ $subscriber->profile_id }}
</p>

        </div>

    </div>
@endsection
