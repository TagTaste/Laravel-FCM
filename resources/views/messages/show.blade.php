@extends('layout')
@section('header')
    <div class="page-header">
        <h1>Message / Show #{{$message->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('messages.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('messages.edit', $message->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Message</label>
<p>
	{{ $message->message }}
</p> <label>Chat_id</label>
<p>
	{{ $message->chat_id }}
</p> <label>Profile_id</label>
<p>
	{{ $message->profile_id }}
</p> <label>Read_on</label>
<p>
	{{ $message->read_on }}
</p>

        </div>

    </div>
@endsection
