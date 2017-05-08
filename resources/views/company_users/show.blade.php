@extends('layout')
@section('header')
    <div class="page-header">
        <h1>CompanyUser / Show #{{$company_user->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('company_users.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('company_users.edit', $company_user->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Company_id</label>
<p>
	{{ $company_user->company_id }}
</p> <label>User_id</label>
<p>
	{{ $company_user->user_id }}
</p>

        </div>

    </div>
@endsection
