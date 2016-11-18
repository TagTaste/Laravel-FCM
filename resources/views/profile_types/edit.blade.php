@extends('layout')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
<div class="page-header">
  <h1><i class="glyphicon glyphicon-edit"></i> ProfileTypes / Edit #{{$profile_type->id}}</h1>
</div>
@endsection

@section('content')
@include('error')

<div class="row">
  <div class="col-md-12">

    <form action="{{ route('profile_types.update', $profile_type->id) }}" method="POST">
      <input type="hidden" name="_method" value="PUT">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">

      <div class="form-group @if($errors->has('type')) has-error @endif">
       <label for="type-field">Type</label>
       <input type="text" id="type-field" name="type" class="form-control" value="{{ is_null(old("type")) ? $profile_type->type : old("type") }}"/>
       @if($errors->has("type"))
       <span class="help-block">{{ $errors->first("type") }}</span>
       @endif
     </div>
     <div class="form-group @if($errors->has('enabled')) has-error @endif">
       <label for="enabled">Enabled</label>
       <div class="btn-group" data-toggle="buttons"><label class="btn btn-primary"><input type="radio" value="1" name="enabled" id="enabled" autocomplete="off"> Yes </label><label class="btn btn-primary active"><input type="radio" name="enabled" value="0" id="enabled" autocomplete="off"> No </label></div>
       @if($errors->has("enabled"))
       <span class="help-block">{{ $errors->first("enabled") }}</span>
       @endif
     </div>
     <div class="form-group @if($errors->has('default')) has-error @endif">
       <label for="default">Default</label>
       <div class="btn-group" data-toggle="buttons"><label class="btn btn-primary"><input type="radio" value="1" name="default" id="default" autocomplete="off"> Yes </label><label class="btn btn-primary active"><input type="radio" name="default" value="0" id="default" autocomplete="off"> No </label></div>
       @if($errors->has("default"))
       <span class="help-block">{{ $errors->first("default") }}</span>
       @endif
     </div>
     <div class="well well-sm">
      <button type="submit" class="btn btn-primary">Save</button>
      <a class="btn btn-link pull-right" href="{{ route('profile_types.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
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
