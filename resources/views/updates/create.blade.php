@extends('layout')

@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-plus"></i> Update / Create </h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('updates.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
	<label for="chema=content-field">Chema=content</label>
	<input class="form-control" type="text" name="chema=content" id="chema=content-field" value="" />
</div> <div class="form-group">
	<label for="model_id-field">Model_id</label>
	--model_id--
</div> <div class="form-group">
	<label for="model_name-field">Model_name</label>
	<input class="form-control" type="text" name="model_name" id="model_name-field" value="" />
</div> <div class="form-group">
	<label for="profile_id-field">Profile_id</label>
	--profile_id--
</div>

                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a class="btn btn-link pull-right" href="{{ route('updates.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
                </div>
            </form>

        </div>
    </div>
@endsection