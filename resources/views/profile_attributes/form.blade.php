@extends('layout')
@section('css')

@endsection
@section('header')
<div class="page-header">
	<h1><i class="glyphicon glyphicon-plus"></i> Profile Form Preview </h1>
</div>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		@include('profile_attrbutes.onlyForm')	
	</div>

</div>


@endsection