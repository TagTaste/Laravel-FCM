@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> Templates / Edit #{{$template->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('templates.update', $template->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('name')) has-error @endif">
                       <label for="name-field">Name</label>
                    <input type="text" id="name-field" name="name" class="form-control" value="{{ is_null(old("name")) ? $template->name : old("name") }}"/>
                       @if($errors->has("name"))
                        <span class="help-block">{{ $errors->first("name") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('view')) has-error @endif">
                       <label for="view-field">View</label>
                    <input type="text" id="view-field" name="view" class="form-control" value="{{ is_null(old("view")) ? $template->view : old("view") }}"/>
                       @if($errors->has("view"))
                        <span class="help-block">{{ $errors->first("view") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('enabled')) has-error @endif">
                       <label for="enabled-field">Enabled</label>
                    <div class="btn-group" data-toggle="buttons"><label class="btn btn-primary"><input type="radio" value="1" name="enabled" id="enabled-field" autocomplete="off"> True</label><label class="btn btn-primary active"><input type="radio" name="enabled" value="0" id="enabled-field" autocomplete="off"> False</label></div>
                       @if($errors->has("enabled"))
                        <span class="help-block">{{ $errors->first("enabled") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('template_type_id')) has-error @endif">
                       <label for="template_type_id-field">Template Type</label>

                      <select name="template_type_id" id="template_type_id-field" class="form-control">
                        @foreach($templateTypes as $name => $id)
                          <option value="{{ $id }}" @if($template->template_type_id == $id) selected @endif> {{ $name }}</option>
                        @endforeach
                      </select>
                       @if($errors->has("template_type_id"))
                        <span class="help-block">{{ $errors->first("template_type_id") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('templates.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
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
