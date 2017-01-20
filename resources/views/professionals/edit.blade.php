@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> Professionals / Edit #{{$professional->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('professionals.update', $professional->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('ingredients')) has-error @endif">
                       <label for="ingredients-field">Ingredients</label>
                    <textarea class="form-control" id="ingredients-field" rows="3" name="ingredients">{{ is_null(old("ingredients")) ? $professional->ingredients : old("ingredients") }}</textarea>
                       @if($errors->has("ingredients"))
                        <span class="help-block">{{ $errors->first("ingredients") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('favourite_moments')) has-error @endif">
                       <label for="favourite_moments-field">Favourite_moments</label>
                    <textarea class="form-control" id="favourite_moments-field" rows="3" name="favourite_moments">{{ is_null(old("favourite_moments")) ? $professional->favourite_moments : old("favourite_moments") }}</textarea>
                       @if($errors->has("favourite_moments"))
                        <span class="help-block">{{ $errors->first("favourite_moments") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('profile_id')) has-error @endif">
                       <label for="profile_id-field">Profile_id</label>
                    <input type="text" id="profile_id-field" name="profile_id" class="form-control" value="{{ is_null(old("profile_id")) ? $professional->profile_id : old("profile_id") }}"/>
                       @if($errors->has("profile_id"))
                        <span class="help-block">{{ $errors->first("profile_id") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('professionals.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
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
