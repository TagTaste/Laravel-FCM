@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> AttributeValues / Edit #{{$attribute_value->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('attribute_values.update', $attribute_value->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('name')) has-error @endif">
                       <label for="name-field">Name</label>
                    <input type="text" id="name-field" name="name" class="form-control" value="{{ is_null(old("name")) ? $attribute_value->name : old("name") }}"/>
                       @if($errors->has("name"))
                        <span class="help-block">{{ $errors->first("name") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('value')) has-error @endif">
                       <label for="value-field">Value</label>
                    <input type="text" id="value-field" name="value" class="form-control" value="{{ is_null(old("value")) ? $attribute_value->value : old("value") }}"/>
                       @if($errors->has("value"))
                        <span class="help-block">{{ $errors->first("value") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('default')) has-error @endif">
                       <label for="default-field">Default</label>
                    <div class="btn-group" data-toggle="buttons"><label class="btn btn-primary"><input type="radio" value="true" name="default-field" id="default-field" autocomplete="off"> True</label><label class="btn btn-primary active"><input type="radio" name="default-field" value="false" id="default-field" autocomplete="off"> False</label></div>
                       @if($errors->has("default"))
                        <span class="help-block">{{ $errors->first("default") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('attribute_id')) has-error @endif">
                       <label for="attribute_id-field">Attribute_id</label>
                    <input type="text" id="attribute_id-field" name="attribute_id" class="form-control" value="{{ is_null(old("attribute_id")) ? $attribute_value->attribute_id : old("attribute_id") }}"/>
                       @if($errors->has("attribute_id"))
                        <span class="help-block">{{ $errors->first("attribute_id") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('attribute_values.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
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
