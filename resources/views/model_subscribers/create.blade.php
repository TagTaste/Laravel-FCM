@extends('layout')

@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-plus"></i> ModelSubscriber / Create </h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('model_subscribers.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
	<label for="model-field">Model</label>
	<input class="form-control" type="text" name="model" id="model-field" value="" />
</div> <div class="form-group">
	<label for="model_id-field">Model_id</label>
	--model_id--
</div> <div class="form-group">
	<label for="profile_id-field">Profile_id</label>
	--profile_id--
</div> <div class="form-group">
	<label for="muted_on-field">Muted_on</label>
	--muted_on--
</div>

                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a class="btn btn-link pull-right" href="{{ route('model_subscribers.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
                </div>
            </form>

        </div>
    </div>
@endsection