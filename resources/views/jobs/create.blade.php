@extends('layout')

@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-plus"></i> Job / Create </h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('jobs.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
	<label for="title-field">Title</label>
	<input class="form-control" type="text" name="title" id="title-field" value="" />
</div> <div class="form-group">
	<label for="description-field">Description</label>
	<textarea name="description" id="description-field" class="form-control" rows="3"></textarea>
</div> <div class="form-group">
	<label for="type-field">Type</label>
	<input class="form-control" type="text" name="type" id="type-field" value="" />
</div> <div class="form-group">
	<label for="location-field">Location</label>
	<input class="form-control" type="text" name="location" id="location-field" value="" />
</div> <div class="form-group">
	<label for="annual_salary-field">Annual_salary</label>
	<input class="form-control" type="text" name="annual_salary" id="annual_salary-field" value="" />
</div> <div class="form-group">
	<label for="functional_area-field">Functional_area</label>
	<input class="form-control" type="text" name="functional_area" id="functional_area-field" value="" />
</div> <div class="form-group">
	<label for="key_skills-field">Key_skills</label>
	<textarea name="key_skills" id="key_skills-field" class="form-control" rows="3"></textarea>
</div> <div class="form-group">
					<label for="expected_role-field">Expected_role</label>
					<input class="form-control" type="text" name="expected_role" id="expected_role-field" value=""/>
</div> <div class="form-group">
	<label for="experience_required-field">Experience_required</label>
	<input class="form-control" type="text" name="experience_required" id="experience_required-field" value="" />
</div> <div class="form-group">
	<label for="company_id-field">Company_id</label>
	--company_id--
</div>

                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a class="btn btn-link pull-right" href="{{ route('jobs.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
                </div>
            </form>

        </div>
    </div>
@endsection