@extends('layout')

@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> ProductCatalogue / Edit #{{$product_catalogue->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('product_catalogues.update', $product_catalogue->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
	<label for="product-field">Product</label>
	<input class="form-control" type="text" name="product" id="product-field" value="{{ old('product', $product_catalogue->product ) }}" />
</div> <div class="form-group">
	<label for="catalogue-field">Catalogue</label>
	<input class="form-control" type="text" name="catalogue" id="catalogue-field" value="{{ old('catalogue', $product_catalogue->catalogue ) }}" />
</div> <div class="form-group">
	<label for="company_id-field">Company_id</label>
	--company_id--
</div>

                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('product_catalogues.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
                </div>
            </form>

        </div>
    </div>
@endsection