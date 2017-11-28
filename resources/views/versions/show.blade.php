@extends('layout')
@section('header')
    <div class="page-header">
        <h1>Version / Show #{{$version->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('versions.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('versions.edit', $version->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Compatible_version</label>
<p>
	{{ $version->compatible_version }}
</p> <label>Latest_version</label>
<p>
	{{ $version->latest_version }}
</p>

        </div>

    </div>
@endsection
