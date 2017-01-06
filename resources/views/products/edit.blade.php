@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> Products / Edit #{{$product->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('name')) has-error @endif">
                       <label for="name-field">Name</label>
                    <input type="text" id="name-field" name="name" class="form-control" value="{{ is_null(old("name")) ? $product->name : old("name") }}"/>
                       @if($errors->has("name"))
                        <span class="help-block">{{ $errors->first("name") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('price')) has-error @endif">
                       <label for="price-field">Price</label>
                    <input type="text" id="price-field" name="price" class="form-control" value="{{ is_null(old("price")) ? $product->price : old("price") }}"/>
                       @if($errors->has("price"))
                        <span class="help-block">{{ $errors->first("price") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('image')) has-error @endif">
                       <label for="image-field">Image</label>
                       <input class="form-control" type="file" name="image"/>
                       @if($errors->has("image"))
                        <span class="help-block">{{ $errors->first("image") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('moq')) has-error @endif">
                       <label for="moq-field">Minimum Order Quantity</label>
                    <input type="text" id="moq-field" name="moq" class="form-control" value="{{ is_null(old("moq")) ? $product->moq : old("moq") }}"/>
                       @if($errors->has("moq"))
                        <span class="help-block">{{ $errors->first("moq") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('type')) has-error @endif">
                       <label for="type-field">Type</label>
                        {!! Form::select('type',$types,old('type'),['class'=>'form-control']) !!}
                       @if($errors->has("type"))
                        <span class="help-block">{{ $errors->first("type") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('about')) has-error @endif">
                       <label for="about-field">About</label>
                    <input type="text" id="about-field" name="about" class="form-control" value="{{ is_null(old("about")) ? $product->about : old("about") }}"/>
                       @if($errors->has("about"))
                        <span class="help-block">{{ $errors->first("about") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('ingredients')) has-error @endif">
                       <label for="ingredients-field">Ingredients</label>
                    <input type="text" id="ingredients-field" name="ingredients" class="form-control" value="{{ is_null(old("ingredients")) ? $product->ingredients : old("ingredients") }}"/>
                       @if($errors->has("ingredients"))
                        <span class="help-block">{{ $errors->first("ingredients") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('certifications')) has-error @endif">
                       <label for="certifications-field">Certifications</label>
                    <input type="text" id="certifications-field" name="certifications" class="form-control" value="{{ is_null(old("certifications")) ? $product->certifications : old("certifications") }}"/>
                       @if($errors->has("certifications"))
                        <span class="help-block">{{ $errors->first("certifications") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('portion_size')) has-error @endif">
                       <label for="portion_size-field">Portion Size</label>
                    <input type="text" id="portion_size-field" name="portion_size" class="form-control" value="{{ is_null(old("portion_size")) ? $product->portion_size : old("portion_size") }}"/>
                       @if($errors->has("portion_size"))
                        <span class="help-block">{{ $errors->first("portion_size") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('shelf_life')) has-error @endif">
                       <label for="shelf_life-field">Shelf Life</label>
                    <input type="text" id="shelf_life-field" name="shelf_life" class="form-control" value="{{ is_null(old("shelf_life")) ? $product->shelf_life : old("shelf_life") }}"/>
                       @if($errors->has("shelf_life"))
                        <span class="help-block">{{ $errors->first("shelf_life") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('mode')) has-error @endif">
                       <label for="mode-field">Mode</label>
                        {!! Form::select('mode',$modes,old('mode'),['class'=>'form-control']) !!}
                       @if($errors->has("mode"))
                        <span class="help-block">{{ $errors->first("mode") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('products.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
                </div>
            </form>

        </div>
    </div>
@endsection
@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script>
  <script>
    $('.date-picker').datepicker({
    });
  </script>
@endsection
