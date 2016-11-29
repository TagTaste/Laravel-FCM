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
	<form method="post" action="{{ route('profiles.updateIndividual') }}" >
		{{ csrf_field() }}
		<input type="hidden" name="typeId" value="{{ $typeId }}">
		<hr>

		@foreach($profileAttributes as $attributes)
			@php
				$first = $attributes->first();
				$required = $first->isRequired();
				$name = "attributes[$first->p_id]";
			@endphp
		
		<div class="form-group">

			<label for="{{$first->name}}-field">
				{{$first->label}} 
				@if($first->required)
					*
				@endif
			</label> 
			<br/>

			@if($first->input_type == "file")
				<input type="file" id="exampleInputFile" name="{{ $name }}" {{ $required }}>
			@elseif($first->input_type == "textarea")
				<textarea class="form-control" rows="3" name="{{ $name }} " {{ $required }}></textarea>

			@elseif($first->input_type == "text")
				<input type="text" class="form-control"  name="{{ $name }}" {{ $required }}
				value = "{{ $first->value }}"
				/>
			@elseif($first->input_type == "checkbox")
				
				@foreach($attributes as $attribute)

					@php

						if(is_null($attribute->p_id)) {
							$name = "new[{$attribute->id}][]";

						} else {
							$name = "attributes[{$attribute->p_id}]";
						}
					@endphp
					<input type="checkbox" name="{{ $name }}" value="{{ $attribute->av_id}}"
					@if(!is_null($attribute->p_value) && $attribute->av_id == $attribute->p_value) checked @endif /> {{ $attribute->av_name }} <br/>
				@endforeach
				
			@elseif($first->input_type == "radio")
			@elseif($first->input_type == "dropdown" || $first->input_type == "dropdown_multiple")
				<select name="attributes[{$first->p_id}][value_id]">
					@foreach($attributes as $attribute)
						<option value="{{ $attribute->av_id }}">{{ $attribute->av_name }}</option>
					@endforeach
				</select>
			
			@endif

			<small>{{ $first->description}}</small>
		</div>
		@endforeach

		<div class="form-group">
					<input type="submit" value="Save" class="btn btn-info">
		</div>
	</form>		
	</div>

</div>


@endsection