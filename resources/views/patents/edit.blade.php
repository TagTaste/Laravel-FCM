@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> Patents / Edit #{{$patent->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('patents.update', $patent->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('title')) has-error @endif">
                       <label for="title-field">Title</label>
                    <input type="text" id="title-field" name="title" class="form-control" value="{{ is_null(old("title")) ? $patent->title : old("title") }}"/>
                       @if($errors->has("title"))
                        <span class="help-block">{{ $errors->first("title") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('description')) has-error @endif">
                       <label for="description-field">Description</label>
                    <textarea class="form-control" id="description-field" rows="3" name="description">{{ is_null(old("description")) ? $patent->description : old("description") }}</textarea>
                       @if($errors->has("description"))
                        <span class="help-block">{{ $errors->first("description") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('number')) has-error @endif">
                       <label for="number-field">Number</label>
                    <input type="text" id="number-field" name="number" class="form-control" value="{{ is_null(old("number")) ? $patent->number : old("number") }}"/>
                       @if($errors->has("number"))
                        <span class="help-block">{{ $errors->first("number") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('issued_by')) has-error @endif">
                       <label for="issued_by-field">Issued_by</label>
                    <input type="text" id="issued_by-field" name="issued_by" class="form-control" value="{{ is_null(old("issued_by")) ? $patent->issued_by : old("issued_by") }}"/>
                       @if($errors->has("issued_by"))
                        <span class="help-block">{{ $errors->first("issued_by") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('awarded_on')) has-error @endif">
                       <label for="awarded_on-field">Awarded_on</label>
                    <input type="text" id="awarded_on-field" name="awarded_on" class="form-control date-picker" value="{{ is_null(old("awarded_on")) ? $patent->awarded_on : old("awarded_on") }}"/>
                       @if($errors->has("awarded_on"))
                        <span class="help-block">{{ $errors->first("awarded_on") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('company_id')) has-error @endif">
                       <label for="company_id-field">Company_id</label>
                    <input type="text" id="company_id-field" name="company_id" class="form-control" value="{{ is_null(old("company_id")) ? $patent->company_id : old("company_id") }}"/>
                       @if($errors->has("company_id"))
                        <span class="help-block">{{ $errors->first("company_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('company_id')) has-error @endif">
                       <label for="company_id-field">Company_id</label>
                    <input type="text" id="company_id-field" name="company_id" class="form-control" value="{{ is_null(old("company_id")) ? $patent->company_id : old("company_id") }}"/>
                       @if($errors->has("company_id"))
                        <span class="help-block">{{ $errors->first("company_id") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('patents.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
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
