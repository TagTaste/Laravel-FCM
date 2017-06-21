@extends('layout')
@section('header')
    <div class="page-header">
        <h1>CollaborateCategory / Show #{{$collaborate_category->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('collaborate_categories.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('collaborate_categories.edit', $collaborate_category->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Chema=name</label>
<p>
	{{ $collaborate_category->chema=name }}
</p> <label>Parent_id</label>
<p>
	{{ $collaborate_category->parent_id }}
</p>

        </div>

    </div>
@endsection
