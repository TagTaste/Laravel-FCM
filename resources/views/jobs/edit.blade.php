@extends('layout')

@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> Job / Edit #{{$job->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('jobs.update', $job->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
	<label for="title-field">Title</label>
	<input class="form-control" type="text" name="title" id="title-field" value="{{ old('title', $job->title ) }}" />
</div> <div class="form-group">
	<label for="description-field">Description</label>
	<textarea name="description" id="description-field" class="form-control" rows="3">{{ old('description', $job->description ) }}</textarea>
</div> <div class="form-group">
	<label for="type-field">Type</label>
	<input class="form-control" type="text" name="type" id="type-field" value="{{ old('type', $job->type ) }}" />
</div> <div class="form-group">
	<label for="location-field">Location</label>
	<input class="form-control" type="text" name="location" id="location-field" value="{{ old('location', $job->location ) }}" />
</div> <div class="form-group">
	<label for="annual_salary-field">Annual_salary</label>
	<input class="form-control" type="text" name="annual_salary" id="annual_salary-field" value="{{ old('annual_salary', $job->annual_salary ) }}" />
</div> <div class="form-group">
	<label for="functional_area-field">Functional_area</label>
	<input class="form-control" type="text" name="functional_area" id="functional_area-field" value="{{ old('functional_area', $job->functional_area ) }}" />
</div> <div class="form-group">
	<label for="key_skills-field">Key_skills</label>
	<textarea name="key_skills" id="key_skills-field" class="form-control" rows="3">{{ old('key_skills', $job->key_skills ) }}</textarea>
</div> <div class="form-group">
	<label for="xpected_role-field">Xpected_role</label>
	<input class="form-control" type="text" name="xpected_role" id="xpected_role-field" value="{{ old('xpected_role', $job->xpected_role ) }}" />
</div> <div class="form-group">
	<label for="experience_required-field">Experience_required</label>
	<input class="form-control" type="text" name="experience_required" id="experience_required-field" value="{{ old('experience_required', $job->experience_required ) }}" />
</div> <div class="form-group">
	<label for="company_id-field">Company_id</label>
	--company_id--
</div>

                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('jobs.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
                </div>
            </form>

        </div>
    </div>
@endsection