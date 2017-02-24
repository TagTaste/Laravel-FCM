@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-plus"></i> CompanyLocations / Create </h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('company_locations.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('address')) has-error @endif">
                       <label for="address-field">Address</label>
                    <textarea class="form-control" id="address-field" rows="3" name="address">{{ old("address") }}</textarea>
                       @if($errors->has("address"))
                        <span class="help-block">{{ $errors->first("address") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('country')) has-error @endif">
                       <label for="country-field">Country</label>
                    <input type="text" id="country-field" name="country" class="form-control" value="{{ old("country") }}"/>
                       @if($errors->has("country"))
                        <span class="help-block">{{ $errors->first("country") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('phone')) has-error @endif">
                       <label for="phone-field">Phone</label>
                    <input type="text" id="phone-field" name="phone" class="form-control" value="{{ old("phone") }}"/>
                       @if($errors->has("phone"))
                        <span class="help-block">{{ $errors->first("phone") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('company_id')) has-error @endif">
                       <label for="company_id-field">Company_id</label>
                    <input type="text" id="company_id-field" name="company_id" class="form-control" value="{{ old("company_id") }}"/>
                       @if($errors->has("company_id"))
                        <span class="help-block">{{ $errors->first("company_id") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a class="btn btn-link pull-right" href="{{ route('company_locations.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
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
