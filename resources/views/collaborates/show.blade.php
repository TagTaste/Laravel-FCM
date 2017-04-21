@extends('layout')
@section('header')
    <div class="page-header">
        <h1>Collaborate / Show #{{$collaborate->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('collaborates.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('collaborates.edit', $collaborate->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Title</label>
<p>
	{{ $collaborate->title }}
</p> <label>I_am</label>
<p>
	{{ $collaborate->i_am }}
</p> <label>Looking_for</label>
<p>
	{{ $collaborate->looking_for }}
</p> <label>Purpose</label>
<p>
	{{ $collaborate->purpose }}
</p> <label>Deliverables</label>
<p>
	{{ $collaborate->deliverables }}
</p> <label>Who_can_help</label>
<p>
	{{ $collaborate->who_can_help }}
</p> <label>Expires_on</label>
<p>
	{{ $collaborate->expires_on }}
</p> <label>Profile_id</label>
<p>
	{{ $collaborate->profile_id }}
</p> <label>Company_id</label>
<p>
	{{ $collaborate->company_id }}
</p>

        </div>

    </div>
@endsection
