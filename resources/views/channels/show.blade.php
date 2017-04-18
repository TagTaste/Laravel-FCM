@extends('layout')
@section('header')
    <div class="page-header">
        <h1>Channel / Show #{{$channel->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('channels.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('channels.edit', $channel->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Name</label>
<p>
	{{ $channel->name }}
</p> <label>Profile_id</label>
<p>
	{{ $channel->profile_id }}
</p>

        </div>

    </div>
@endsection
