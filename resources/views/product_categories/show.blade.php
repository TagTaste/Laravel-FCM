@extends('layout')
@section('header')
    <div class="page-header">
        <h1>ProductCategory / Show #{{$product_category->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('product_categories.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('product_categories.edit', $product_category->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Chema=product_id</label>
<p>
	{{ $product_category->chema=product_id }}
</p> <label>Category_id</label>
<p>
	{{ $product_category->category_id }}
</p>

        </div>

    </div>
@endsection
