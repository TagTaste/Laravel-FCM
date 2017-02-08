@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> Advertisements / Edit #{{$advertisement->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('advertisements.update', $advertisement->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('title')) has-error @endif">
                       <label for="title-field">Title</label>
                    <input type="text" id="title-field" name="title" class="form-control" value="{{ is_null(old("title")) ? $advertisement->title : old("title") }}"/>
                       @if($errors->has("title"))
                        <span class="help-block">{{ $errors->first("title") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('description')) has-error @endif">
                       <label for="description-field">Description</label>
                    <textarea class="form-control" id="description-field" rows="3" name="description">{{ is_null(old("description")) ? $advertisement->description : old("description") }}</textarea>
                       @if($errors->has("description"))
                        <span class="help-block">{{ $errors->first("description") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('youtube_url')) has-error @endif">
                       <label for="youtube_url-field">Youtube_url</label>
                    <input type="text" id="youtube_url-field" name="youtube_url" class="form-control" value="{{ is_null(old("youtube_url")) ? $advertisement->youtube_url : old("youtube_url") }}"/>
                       @if($errors->has("youtube_url"))
                        <span class="help-block">{{ $errors->first("youtube_url") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('video')) has-error @endif">
                       <label for="video-field">Video</label>
                    <input type="text" id="video-field" name="video" class="form-control" value="{{ is_null(old("video")) ? $advertisement->video : old("video") }}"/>
                       @if($errors->has("video"))
                        <span class="help-block">{{ $errors->first("video") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('company_id')) has-error @endif">
                       <label for="company_id-field">Company_id</label>
                    <input type="text" id="company_id-field" name="company_id" class="form-control" value="{{ is_null(old("company_id")) ? $advertisement->company_id : old("company_id") }}"/>
                       @if($errors->has("company_id"))
                        <span class="help-block">{{ $errors->first("company_id") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('company_id')) has-error @endif">
                       <label for="company_id-field">Company_id</label>
                    <input type="text" id="company_id-field" name="company_id" class="form-control" value="{{ is_null(old("company_id")) ? $advertisement->company_id : old("company_id") }}"/>
                       @if($errors->has("company_id"))
                        <span class="help-block">{{ $errors->first("company_id") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('advertisements.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
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
