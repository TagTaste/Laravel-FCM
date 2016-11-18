@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> Profiles / Edit #{{$profile->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('profiles.update', $profile->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('user_id')) has-error @endif">
                       <label for="user_id-field">User_id</label>
                    <input type="text" id="user_id-field" name="user_id" class="form-control" value="{{ is_null(old("user_id")) ? $profile->user_id : old("user_id") }}"/>
                       @if($errors->has("user_id"))
                        <span class="help-block">{{ $errors->first("user_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('attribute_id')) has-error @endif">
                       <label for="attribute_id-field">Attribute_id</label>
                    <input type="text" id="attribute_id-field" name="attribute_id" class="form-control" value="{{ is_null(old("attribute_id")) ? $profile->attribute_id : old("attribute_id") }}"/>
                       @if($errors->has("attribute_id"))
                        <span class="help-block">{{ $errors->first("attribute_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('value')) has-error @endif">
                       <label for="value-field">Value</label>
                    <input type="text" id="value-field" name="value" class="form-control" value="{{ is_null(old("value")) ? $profile->value : old("value") }}"/>
                       @if($errors->has("value"))
                        <span class="help-block">{{ $errors->first("value") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('type_id')) has-error @endif">
                       <label for="type_id-field">Type_id</label>
                    <input type="text" id="type_id-field" name="type_id" class="form-control" value="{{ is_null(old("type_id")) ? $profile->type_id : old("type_id") }}"/>
                       @if($errors->has("type_id"))
                        <span class="help-block">{{ $errors->first("type_id") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('profiles.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
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
