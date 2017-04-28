@extends('layout')
@section('header')
    <div class="page-header">
        <h1>ShoutoutLike / Show #{{$shoutout_like->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('shoutout_likes.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('shoutout_likes.edit', $shoutout_like->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Profile_id</label>
<p>
	{{ $shoutout_like->profile_id }}
</p> <label>Shoutout_id</label>
<p>
	{{ $shoutout_like->shoutout_id }}
</p>

        </div>

    </div>
@endsection
