@extends('layout')
@section('header')
    <div class="page-header">
        <h1>ProductCatalogue / Show #{{$product_catalogue->id}}</h1>
    </div>
@endsection

@section('content')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('product_catalogues.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
            <div class="col-md-6">
                 <a class="btn btn-sm btn-warning pull-right" href="{{ route('product_catalogues.edit', $product_catalogue->id) }}">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <label>Product</label>
<p>
	{{ $product_catalogue->product }}
</p> <label>Catalogue</label>
<p>
	{{ $product_catalogue->catalogue }}
</p> <label>Company_id</label>
<p>
	{{ $product_catalogue->company_id }}
</p>

        </div>

    </div>
@endsection
