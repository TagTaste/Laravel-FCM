@extends('layout')

@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> JobType / Edit #{{$job_type->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('job_types.update', $job_type->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                    <label for="name-field">Name</label>
                    <input class="form-control" type="text" name="name" id="name-field"
                           value="{{ old('name', $job_type->name ) }}"/>
                </div>
                <div class="form-group">
                    <label for="description-field">Description</label>
                    <textarea name="description" id="description-field" class="form-control"
                              rows="3">{{ old('description', $job_type->description ) }}</textarea>
                </div>

                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('job_types.index') }}"><i
                                class="glyphicon glyphicon-backward"></i> Back</a>
                </div>
            </form>

        </div>
    </div>
@endsection