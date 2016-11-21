@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-plus"></i> Followers / Create </h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('followers.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('chef_id')) has-error @endif">
                       <label for="chef_id-field">Chef_id</label>
                    <input type="text" id="chef_id-field" name="chef_id" class="form-control" value="{{ old("chef_id") }}"/>
                       @if($errors->has("chef_id"))
                        <span class="help-block">{{ $errors->first("chef_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('follower_id')) has-error @endif">
                       <label for="follower_id-field">Follower_id</label>
                    <input type="text" id="follower_id-field" name="follower_id" class="form-control" value="{{ old("follower_id") }}"/>
                       @if($errors->has("follower_id"))
                        <span class="help-block">{{ $errors->first("follower_id") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a class="btn btn-link pull-right" href="{{ route('followers.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
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
