@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> Cuisines / Edit #{{$cuisine->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('cuisines.update', $cuisine->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('name')) has-error @endif">
                       <label for="name-field">Name</label>
                    <input type="text" id="name-field" name="name" class="form-control" value="{{ is_null(old("name")) ? $cuisine->name : old("name") }}"/>
                       @if($errors->has("name"))
                        <span class="help-block">{{ $errors->first("name") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('public')) has-error @endif">
                       <label for="public-field">Public</label>
                    <div class="btn-group" data-toggle="buttons"><label class="btn btn-primary"><input type="radio" value="true" name="public-field" id="public-field" autocomplete="off"> True</label><label class="btn btn-primary active"><input type="radio" name="public-field" value="false" id="public-field" autocomplete="off"> False</label></div>
                       @if($errors->has("public"))
                        <span class="help-block">{{ $errors->first("public") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('count')) has-error @endif">
                       <label for="count-field">Count</label>
                    <input type="text" id="count-field" name="count" class="form-control" value="{{ is_null(old("count")) ? $cuisine->count : old("count") }}"/>
                       @if($errors->has("count"))
                        <span class="help-block">{{ $errors->first("count") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('cuisines.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
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
