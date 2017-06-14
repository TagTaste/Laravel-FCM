@extends('layout')
@section('header')
    <div class="page-header">
        <h1>CompanyCatalogue / Show #{{$company_catalogue->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('company_catalogues.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('company_catalogues.edit', $company_catalogue->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Chema=company_id</label>
<p>
	{{ $company_catalogue->chema=company_id }}
</p> <label>Image</label>
<p>
	{{ $company_catalogue->image }}
</p>

        </div>

    </div>
@endsection
