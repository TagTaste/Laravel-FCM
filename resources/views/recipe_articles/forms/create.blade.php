 @if($dishes)
 <div class="form-group @if($errors->has('dish_id')) has-error @endif">
 <label for="dish_id-field">Dish</label>

	<select name="recipe[dish_id]" id="" class="form-control">
		@foreach($dishes as $id => $name)
			<option value="{{$id}}"> {{$name}} </option>
		@endforeach
	</select>
	@if($errors->has("dish_id"))
 		<span class="help-block">{{ $errors->first("recipe.dish_id") }}</span>
 	@endif
 @endif

</div>
<div class="form-group @if($errors->has('step')) has-error @endif">
 <label for="step-field">Step</label>
 <input type="text" id="step-field" name="recipe[step]" class="form-control" value="{{ old("dish.step") }}"/>
 @if($errors->has("step"))
 <span class="help-block">{{ $errors->first("recipe.step") }}</span>
 @endif
</div>
<div class="form-group @if($errors->has('content')) has-error @endif">
 <label for="content-field">Content</label>
 <textarea class="form-control" id="content-field" rows="3" name="recipe[content]">{{ old("dish.content") }}</textarea>
 @if($errors->has("content"))
 <span class="help-block">{{ $errors->first("recipe.content") }}</span>
 @endif
</div>
{{-- <!-- <div class="form-group @if($errors->has('template_id')) has-error @endif">
 <label for="template_id-field">Template_id</label>
 <input type="text" id="template_id-field" name="template_id" class="form-control" value="{{ old("template_id") }}"/>
 @if($errors->has("template_id"))
 <span class="help-block">{{ $errors->first("template_id") }}</span>
 @endif
</div> --> --}}
<div class="form-group @if($errors->has('parent_id')) has-error @endif">
 <label for="parent_id-field">Parent_id</label>
 <input type="text" id="parent_id-field" name="parent_id" class="form-control" value="{{ old("parent_id") }}"/>
 @if($errors->has("parent_id"))
 <span class="help-block">{{ $errors->first("parent_id") }}</span>
 @endif
</div>