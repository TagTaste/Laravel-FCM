@extends('layout')

@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> CollaborateCategory / Edit #{{$collaborate_category->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('collaborate_categories.update', $collaborate_category->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
	<label for="chema=name-field">Chema=name</label>
	<input class="form-control" type="text" name="chema=name" id="chema=name-field" value="{{ old('chema=name', $collaborate_category->chema=name ) }}" />
</div> <div class="form-group">
	<label for="parent_id-field">Parent_id</label>
	--parent_id--
</div>

                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('collaborate_categories.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
                </div>
            </form>

        </div>
    </div>
@endsection