@extends('layout')
@section('header')
    <div class="page-header">
        <h1>Shoutout / Show #{{$shoutout->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('shoutouts.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('shoutouts.edit', $shoutout->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Content</label>
<p>
	{{ $shoutout->content }}
</p> <label>Profile_id</label>
<p>
	{{ $shoutout->profile_id }}
</p> <label>Company_id</label>
<p>
	{{ $shoutout->company_id }}
</p> <label>Flag</label>
<p>
	{{ $shoutout->flag }}
</p>

        </div>

    </div>
@endsection
