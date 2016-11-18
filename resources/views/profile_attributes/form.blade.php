@extends('layout')
@section('css')

@endsection
@section('header')
<div class="page-header">
	<h1><i class="glyphicon glyphicon-plus"></i> Profile Form Preview </h1>
</div>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		@foreach($profileAttributes as $attribute)
		<?php $required = $attribute->isRequired();?>
		<div class="form-group">

			<label for="{{$attribute->name}}-field">
				{{$attribute->label}} 
				@if($attribute->required)
					*
				@endif
			</label>

			@if($attribute->requires_upload)
				<input type="file" id="exampleInputFile" {{ $required }}>
			@elseif($attribute->multiline)
				<textarea class="form-control" rows="3" {{ $required }}></textarea>
			@else
				<input type="text" class="form-control"  name="attributes[{{$attribute->id}}][value]" {{ $required }}/>
			@endif

			<small>{{ $attribute->description}}</small>
		</div>
		@endforeach
	</div>
</div>


@endsection