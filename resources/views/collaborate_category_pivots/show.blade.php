@extends('layout')
@section('header')
    <div class="page-header">
        <h1>CollaborateCategoryPivot / Show #{{$collaborate_category_pivot->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('collaborate_category_pivots.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('collaborate_category_pivots.edit', $collaborate_category_pivot->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Chema=collaborate_id</label>
<p>
	{{ $collaborate_category_pivot->chema=collaborate_id }}
</p> <label>Category_id</label>
<p>
	{{ $collaborate_category_pivot->category_id }}
</p>

        </div>

    </div>
@endsection
