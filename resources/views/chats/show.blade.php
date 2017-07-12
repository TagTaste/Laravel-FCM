@extends('layout')
@section('header')
    <div class="page-header">
        <h1>Chat / Show #{{$chat->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('chats.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('chats.edit', $chat->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Name</label>
<p>
	{{ $chat->name }}
</p> <label>Profile_id</label>
<p>
	{{ $chat->profile_id }}
</p>

        </div>

    </div>
@endsection
