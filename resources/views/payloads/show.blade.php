@extends('layout')
@section('header')
    <div class="page-header">
        <h1>Payload / Show #{{$payload->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('payloads.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('payloads.edit', $payload->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Channel_name</label>
<p>
	{{ $payload->channel_name }}
</p> <label>Payload</label>
<p>
	{{ $payload->payload }}
</p>

        </div>

    </div>
@endsection
