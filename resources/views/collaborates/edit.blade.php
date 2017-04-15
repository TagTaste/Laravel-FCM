@extends('layout')

@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> Collaborate / Edit #{{$collaborate->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('collaborates.update', $collaborate->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
	<label for="title-field">Title</label>
	<input class="form-control" type="text" name="title" id="title-field" value="{{ old('title', $collaborate->title ) }}" />
</div> <div class="form-group">
	<label for="i_am-field">I_am</label>
	<input class="form-control" type="text" name="i_am" id="i_am-field" value="{{ old('i_am', $collaborate->i_am ) }}" />
</div> <div class="form-group">
	<label for="looking_for-field">Looking_for</label>
	<textarea name="looking_for" id="looking_for-field" class="form-control" rows="3">{{ old('looking_for', $collaborate->looking_for ) }}</textarea>
</div> <div class="form-group">
	<label for="purpose-field">Purpose</label>
	<textarea name="purpose" id="purpose-field" class="form-control" rows="3">{{ old('purpose', $collaborate->purpose ) }}</textarea>
</div> <div class="form-group">
	<label for="deliverables-field">Deliverables</label>
	<textarea name="deliverables" id="deliverables-field" class="form-control" rows="3">{{ old('deliverables', $collaborate->deliverables ) }}</textarea>
</div> <div class="form-group">
	<label for="who_can_help-field">Who_can_help</label>
	<textarea name="who_can_help" id="who_can_help-field" class="form-control" rows="3">{{ old('who_can_help', $collaborate->who_can_help ) }}</textarea>
</div> <div class="form-group">
	<label for="expires_on-field">Expires_on</label>
	--expires_on--
</div> <div class="form-group">
	<label for="profile_id-field">Profile_id</label>
	--profile_id--
</div> <div class="form-group">
	<label for="company_id-field">Company_id</label>
	--company_id--
</div>

                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('collaborates.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
                </div>
            </form>

        </div>
    </div>
@endsection