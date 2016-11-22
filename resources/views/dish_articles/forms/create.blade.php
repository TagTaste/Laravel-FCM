

                <input type="hidden" name="_token" value="{{ csrf_token() }}">

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
                    {{--

                      <!-- <div class="form-group @if($errors->has('article_id')) has-error @endif">
                       <label for="article_id-field">Article_id</label>
                    <input type="text" id="article_id-field" name="article_id" class="form-control" value="{{ old("article_id") }}"/>
                       @if($errors->has("article_id"))
                        <span class="help-block">{{ $errors->first("article_id") }}</span>
                       @endif
                    </div> 
                    <div class="form-group @if($errors->has('chef_id')) has-error @endif">
                       <label for="chef_id-field">Chef_id</label>
                    <input type="text" id="chef_id-field" name="chef_id" class="form-control" value="{{ old("chef_id") }}"/>
                       @if($errors->has("chef_id"))
                        <span class="help-block">{{ $errors->first("chef_id") }}</span>
                       @endif
                    </div> -->


                    --}}
