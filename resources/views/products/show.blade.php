@extends('layout')
@section('header')
<div class="page-header">
        <h1>Products / Show #{{$product->id}}</h1>
        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('products.edit', $product->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                <button type="submit" class="btn btn-danger">Delete <i class="glyphicon glyphicon-trash"></i></button>
            </div>
        </form>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <form action="#">
                <div class="form-group">
                    <label for="nome">ID</label>
                    <p class="form-control-static"></p>
                </div>
                <div class="form-group">
                     <label for="name">NAME</label>
                     <p class="form-control-static">{{$product->name}}</p>
                </div>
                    <div class="form-group">
                     <label for="price">PRICE</label>
                     <p class="form-control-static">{{$product->price}}</p>
                </div>
                    <div class="form-group">
                     <label for="image">IMAGE</label>
                     <img src="/product/image/{{$product->image}}" alt="" height="300px" width="auto">
                </div>
                    <div class="form-group">
                     <label for="moq">MOQ</label>
                     <p class="form-control-static">{{$product->moq}}</p>
                </div>
                <div class="form-group">
                     <label for="type">Type</label>
                     <p class="form-control-static">{{$product->type}}</p>
                </div>
                <div class="form-group">
                     <label for="about">About</label>
                     <p class="form-control-static">{{$product->about}}</p>
                </div>
                <div class="form-group">
                     <label for="ingredients">Ingredients</label>
                     <p class="form-control-static">{{$product->ingredients}}</p>
                </div>
                <div class="form-group">
                     <label for="certifications">Certifications</label>
                     <p class="form-control-static">{{$product->certifications}}</p>
                </div>
                <div class="form-group">
                     <label for="portion_size">Portion Size</label>
                     <p class="form-control-static">{{$product->portion_size}}</p>
                </div>
                <div class="form-group">
                     <label for="shelf_life">Shelf Life</label>
                     <p class="form-control-static">{{$product->shelf_life}}</p>
                </div>
                <div class="form-group">
                     <label for="mode">Mode</label>
                     <p class="form-control-static">{{$product->mode}}</p>
                </div>
            
            </form>

            <a class="btn btn-link" href="{{ route('products.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection