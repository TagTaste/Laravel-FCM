@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> Portfolios / Edit #{{$portfolio->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('portfolios.update', $portfolio->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('worked_for')) has-error @endif">
                       <label for="worked_for-field">Worked_for</label>
                    <textarea class="form-control" id="worked_for-field" rows="3" name="worked_for">{{ is_null(old("worked_for")) ? $portfolio->worked_for : old("worked_for") }}</textarea>
                       @if($errors->has("worked_for"))
                        <span class="help-block">{{ $errors->first("worked_for") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('description')) has-error @endif">
                       <label for="description-field">Description</label>
                    <textarea class="form-control" id="description-field" rows="3" name="description">{{ is_null(old("description")) ? $portfolio->description : old("description") }}</textarea>
                       @if($errors->has("description"))
                        <span class="help-block">{{ $errors->first("description") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('company_id')) has-error @endif">
                       <label for="company_id-field">Company_id</label>
                    <input type="text" id="company_id-field" name="company_id" class="form-control" value="{{ is_null(old("company_id")) ? $portfolio->company_id : old("company_id") }}"/>
                       @if($errors->has("company_id"))
                        <span class="help-block">{{ $errors->first("company_id") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('portfolios.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
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
