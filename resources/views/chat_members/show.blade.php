@extends('layout')
@section('header')
    <div class="page-header">
        <h1>ChatMember / Show #{{$chat_member->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('chat_members.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('chat_members.edit', $chat_member->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Chat_id</label>
<p>
	{{ $chat_member->chat_id }}
</p> <label>Profile_id</label>
<p>
	{{ $chat_member->profile_id }}
</p>

        </div>

    </div>
@endsection
