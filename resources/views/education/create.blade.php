@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-plus"></i> Education / Create </h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('education.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('degree')) has-error @endif">
                       <label for="degree-field">Degree</label>
                    <input type="text" id="degree-field" name="degree" class="form-control" value="{{ old("degree") }}"/>
                       @if($errors->has("degree"))
                        <span class="help-block">{{ $errors->first("degree") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('college')) has-error @endif">
                       <label for="college-field">College</label>
                    <input type="text" id="college-field" name="college" class="form-control" value="{{ old("college") }}"/>
                       @if($errors->has("college"))
                        <span class="help-block">{{ $errors->first("college") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('field')) has-error @endif">
                       <label for="field-field">Field</label>
                    <input type="text" id="field-field" name="field" class="form-control" value="{{ old("field") }}"/>
                       @if($errors->has("field"))
                        <span class="help-block">{{ $errors->first("field") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('grade')) has-error @endif">
                       <label for="grade-field">Grade</label>
                    <input type="text" id="grade-field" name="grade" class="form-control" value="{{ old("grade") }}"/>
                       @if($errors->has("grade"))
                        <span class="help-block">{{ $errors->first("grade") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('percentage')) has-error @endif">
                       <label for="percentage-field">Percentage</label>
                    <input type="text" id="percentage-field" name="percentage" class="form-control" value="{{ old("percentage") }}"/>
                       @if($errors->has("percentage"))
                        <span class="help-block">{{ $errors->first("percentage") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('description')) has-error @endif">
                       <label for="description-field">Description</label>
                    <textarea class="form-control" id="description-field" rows="3" name="description">{{ old("description") }}</textarea>
                       @if($errors->has("description"))
                        <span class="help-block">{{ $errors->first("description") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('start_date')) has-error @endif">
                       <label for="start_date-field">Start_date</label>
                    <input type="text" id="start_date-field" name="start_date" class="form-control date-picker" value="{{ old("start_date") }}"/>
                       @if($errors->has("start_date"))
                        <span class="help-block">{{ $errors->first("start_date") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('end_date')) has-error @endif">
                       <label for="end_date-field">End_date</label>
                    <input type="text" id="end_date-field" name="end_date" class="form-control date-picker" value="{{ old("end_date") }}"/>
                       @if($errors->has("end_date"))
                        <span class="help-block">{{ $errors->first("end_date") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('ongoing')) has-error @endif">
                       <label for="ongoing-field">Ongoing</label>
                    <div class="btn-group" data-toggle="buttons"><label class="btn btn-primary"><input type="radio" value="true" name="ongoing-field" id="ongoing-field" autocomplete="off"> True</label><label class="btn btn-primary active"><input type="radio" name="ongoing-field" value="false" id="ongoing-field" autocomplete="off"> False</label></div>
                       @if($errors->has("ongoing"))
                        <span class="help-block">{{ $errors->first("ongoing") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('profile_id')) has-error @endif">
                       <label for="profile_id-field">Profile_id</label>
                    <input type="text" id="profile_id-field" name="profile_id" class="form-control" value="{{ old("profile_id") }}"/>
                       @if($errors->has("profile_id"))
                        <span class="help-block">{{ $errors->first("profile_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('profile_id')) has-error @endif">
                       <label for="profile_id-field">Profile_id</label>
                    <input type="text" id="profile_id-field" name="profile_id" class="form-control" value="{{ old("profile_id") }}"/>
                       @if($errors->has("profile_id"))
                        <span class="help-block">{{ $errors->first("profile_id") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a class="btn btn-link pull-right" href="{{ route('education.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
                </div>
            </form>

        </div>
    </div>
@endsection
@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script>
  <script>
    $('.date-picker').datepicker({
    });
  </script>
@endsection
