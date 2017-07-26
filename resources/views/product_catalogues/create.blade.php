@extends('layout')

@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-plus"></i> ProductCatalogue / Create </h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('product_catalogues.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
	<label for="product-field">Product</label>
	<input class="form-control" type="text" name="product" id="product-field" value="" />
</div> <div class="form-group">
	<label for="catalogue-field">Catalogue</label>
	<input class="form-control" type="text" name="catalogue" id="catalogue-field" value="" />
</div> <div class="form-group">
	<label for="company_id-field">Company_id</label>
	--company_id--
</div>

                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a class="btn btn-link pull-right" href="{{ route('product_catalogues.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
                </div>
            </form>

        </div>
    </div>
@endsection