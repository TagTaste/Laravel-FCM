@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> Products
            <a class="btn btn-success pull-right" href="{{ route('products.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($products->count())
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NAME</th>
                        <th>PRICE</th>
                        <th>MOQ</th>
                        <th>IMAGE</th>
                        <th>Type</th>
                        <th>About</th>
                        <th>Ingredients</th>
                        <th>Certifications</th>
                        <th>Portion Size</th>
                        <th>Shelf Life</th>
                        <th>Mode</th>
                   
                            <th class="text-right">OPTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{$product->id}}</td>
                                <td>{{$product->name}}</td>
                    <td>{{$product->price}}</td>
                    <td>{{$product->moq}}</td>
                    <td><img src="/product/image/{{$product->image}}" alt="" height="100px" width="auto"></td>
                    <td>{{$product->getType()}}</td>
                    <td>{{$product->about}}</td>
                    <td>{{$product->ingredients}}</td>
                    <td>{{$product->certifications}}</td>
                    <td>{{$product->portion_size}}</td>
                    <td>{{$product->shelf_life}}</td>
                    <td>{{$product->getMode()}}</td>
                                <td class="text-right">
                                    <a class="btn btn-xs btn-primary" href="{{ route('products.show', $product->id) }}"><i class="glyphicon glyphicon-eye-open"></i> View</a>
                                    <a class="btn btn-xs btn-warning" href="{{ route('products.edit', $product->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $products->render() !!}
            @else
                <h3 class="text-center alert alert-info">Empty!</h3>
            @endif

        </div>
    </div>

@endsection