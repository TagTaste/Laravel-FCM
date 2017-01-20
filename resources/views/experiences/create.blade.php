@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-plus"></i> Experiences / Create </h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('experiences.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('company')) has-error @endif">
                       <label for="company-field">Company</label>
                    <input type="text" id="company-field" name="company" class="form-control" value="{{ old("company") }}"/>
                       @if($errors->has("company"))
                        <span class="help-block">{{ $errors->first("company") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('designation')) has-error @endif">
                       <label for="designation-field">Designation</label>
                    <input type="text" id="designation-field" name="designation" class="form-control" value="{{ old("designation") }}"/>
                       @if($errors->has("designation"))
                        <span class="help-block">{{ $errors->first("designation") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('description')) has-error @endif">
                       <label for="description-field">Description</label>
                    <textarea class="form-control" id="description-field" rows="3" name="description">{{ old("description") }}</textarea>
                       @if($errors->has("description"))
                        <span class="help-block">{{ $errors->first("description") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('location')) has-error @endif">
                       <label for="location-field">Location</label>
                    <textarea class="form-control" id="location-field" rows="3" name="location">{{ old("location") }}</textarea>
                       @if($errors->has("location"))
                        <span class="help-block">{{ $errors->first("location") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('start_date')) has-error @endif">
                       <label for="start_date-field">Start End</label>
                    <input type="text" id="start_date-field" name="start_date" class="form-control date-picker" value="{{ old("start_date") }}"/>
                       @if($errors->has("start_date"))
                        <span class="help-block">{{ $errors->first("start_date") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('end_date')) has-error @endif">
                       <label for="end_date-field">End Date</label>
                    <input type="text" id="end_date-field" name="end_date" class="form-control date-picker" value="{{ old("end_date") }}"/>
                       @if($errors->has("end_date"))
                        <span class="help-block">{{ $errors->first("end_date") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('current_company')) has-error @endif">
                       <label for="current_company">Current Company</label>
                    <div class="btn-group" data-toggle="buttons"><label class="btn btn-primary"><input type="radio" value="1" name="current_company" id="current_company" autocomplete="off"> True</label><label class="btn btn-primary active"><input type="radio" name="current_company" value="0" id="current_company" autocomplete="off"> False</label></div>
                       @if($errors->has("current_company"))
                        <span class="help-block">{{ $errors->first("current_company") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a class="btn btn-link pull-right" href="{{ route('experiences.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
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
