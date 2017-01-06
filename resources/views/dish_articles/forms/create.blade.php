

                <div class="form-group @if($errors->has('description')) has-error @endif">
                       <label for="description-field">Description</label>
                       <textarea id="description-field" name="dish[description]" class="form-control" rows="10">{{ old("description") }}</textarea>
                       @if($errors->has("description"))
                       <span class="help-block">{{ $errors->first("description") }}</span>
                       @endif
                     </div>
                
                <div class="form-group @if($errors->has('ingredients')) has-error @endif">
                       <label for="ingredients-field">Ingredients</label>
                       <textarea id="ingredients-field" name="dish[ingredients]" class="form-control" rows="10">{{ old("ingredients") }}</textarea>
                       @if($errors->has("ingredients"))
                       <span class="help-block">{{ $errors->first("ingredients") }}</span>
                       @endif
                     </div>

                     <div class="form-group @if($errors->has('image')) has-error @endif">
                       <label for="image-field">Image</label>
                    <input type="file" id="image-field" name="dish[image]" class="form-control"/>
                       @if($errors->has("image"))
                        <span class="help-block">{{ $errors->first("image") }}</span>
                       @endif
                    </div>

                    <div class="form-group @if($errors->has('category')) has-error @endif">
                     <label for="category-field">Category</label>
                     <input type="text" id="category-field" name="dish[category]" class="form-control" value="{{ old("category") }}"/>
                     @if($errors->has("category"))
                      <span class="help-block">{{ $errors->first("category") }}</span>
                      @endif
                   </div>

                   <div class="form-group @if($errors->has('serving')) has-error @endif">
                     <label for="serving-field">Serving</label>
                     <input type="text" id="serving-field" name="dish[serving]" class="form-control" value="{{ old("serving") }}"/>
                     @if($errors->has("serving"))
                      <span class="help-block">{{ $errors->first("serving") }}</span>
                      @endif
                   </div>

                   <div class="form-group @if($errors->has('calorie')) has-error @endif">
                     <label for="calorie-field">Calorie</label>
                     <input type="text" id="calorie-field" name="dish[calorie]" class="form-control" value="{{ old("calorie") }}"/>
                     @if($errors->has("calorie"))
                      <span class="help-block">{{ $errors->first("calorie") }}</span>
                      @endif
                   </div>

                   <div class="form-group @if($errors->has('time')) has-error @endif">
                     <label for="time-field">Time</label>
                     <input type="text" id="time-field" name="dish[time]" class="form-control" value="{{ old("time") }}"/>
                     @if($errors->has("time"))
                      <span class="help-block">{{ $errors->first("time") }}</span>
                      @endif
                   </div>

                    <div class="form-group @if($errors->has('showcase')) has-error @endif">
                       <label for="showcase-field">Showcase</label>
                        <div class="btn-group" data-toggle="buttons"><label class="btn btn-primary"><input type="radio" value="1" name="dish[showcase]" id="showcase-field" autocomplete="off"> True</label><label class="btn btn-primary active"><input type="radio" name="dish[showcase]" value="0" id="showcase-field" autocomplete="off"> False</label></div>
                        @if($errors->has("showcase"))
                          <span class="help-block">{{ $errors->first("showcase") }}</span>
                        @endif
                    </div>

                    <div class="form-group @if($errors->has('hasrecipe')) has-error @endif">
                       <label for="hasrecipe-field">HasRecipe</label>
                    <div class="btn-group" data-toggle="buttons"><label class="btn btn-primary"><input type="radio" value="1" name="dish[hasrecipe] id="hasrecipe-field" autocomplete="off"> True</label><label class="btn btn-primary active"><input type="radio" name="dish[hasrecipe]" value="0" id="hasrecipe-field" autocomplete="off"> False</label></div>
                       @if($errors->has("hasrecipe"))
                        <span class="help-block">{{ $errors->first("hasrecipe") }}</span>
                       @endif
                    </div>
