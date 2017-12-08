@extends('layout')
@section('header')
    <div class="page-header">
        <h1>ChatLimit / Show #{{$chat_limit->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('chat_limits.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('chat_limits.edit', $chat_limit->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Profile_id</label>
<p>
	{{ $chat_limit->profile_id }}
</p> <label>Remaining</label>
<p>
	{{ $chat_limit->remaining }}
</p> <label>Max</label>
<p>
	{{ $chat_limit->max }}
</p>

        </div>

    </div>
@endsection
