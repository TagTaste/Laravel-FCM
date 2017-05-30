@extends('layout')
@section('header')
    <div class="page-header">
        <h1>Category / Show #{{$category->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('categories.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('categories.edit', $category->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Id</label>
<p>
	{{ $category->id }}
</p> <label>Name</label>
<p>
	{{ $category->name }}
</p> <label>Parent_id</label>
<p>
	{{ $category->parent_id }}
</p>

        </div>

    </div>
@endsection
