@extends('layout')
@section('header')
    <div class="page-header">
        <h1>PhotoLike / Show #{{$photo_like->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('photo_likes.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('photo_likes.edit', $photo_like->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>“photo_id</label>
<p>
	{{ $photo_like->“photo_id }}
</p>

        </div>

    </div>
@endsection
