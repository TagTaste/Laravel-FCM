@extends('layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> RecipeArticles / Edit #{{$dish_article->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">
          @if(count($dish_article->recipe) > 0)
            <form action="{{ route('recipe_articles.update', $dish_article->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="id" value="{{ $dish_article->id }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row" id="addMoreFields">
                  @foreach($dish_article->recipe as $key => $value)
                    <div class="form-group" id="removeBlock{{$key+1}}">
                        <input type="hidden" name="recipe_id{{$key}}" value="{{ $value['id'] }}">
                        <span class="glyphicon glyphicon-remove removerecipe" style="color: blue; cursor: pointer;" length="{{$key+1}}" data-toggle="tooltip" title="Remove" value="{{$value['id']}}"></span>
                        <label for="content-field">Step</label>
                        <input type="text" name="content[]" class="form-control" value="{{$value->content}}" />
                    </div>
                  @endforeach
                </div>
              <div class="well well-sm">
                  <button class="btn btn-primary" type="button" id="addrecipe"><strong>Add Step</strong></button>
                  <button type="submit" class="btn btn-primary">Save</button>
                  <a class="btn btn-link pull-right" href="{{ route('articles.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
              </div>
            </form>
          @else
            No recipe added yet.
          @endif
        </div>
    </div>
@endsection
@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script>
  <script>
    $('.date-picker').datepicker({
    });

    $(document).ready(function() {
        var max_recipe_fields = 50;
        var wrapperrecipe = $("form #addMoreFields");
        var curr_recipe_length = $('form #addMoreFields').find('[name^="content"]').length;
        var curr_recipe_id = curr_recipe_length;
        $("form #addrecipe").click(function(e) {
            e.preventDefault();
            if(curr_recipe_length < max_recipe_fields) {
                curr_recipe_length++;
                curr_recipe_id++;
                var htmlrecipeField = "<div class='form-group' id='removeBlock"+curr_recipe_id+"'><span class='glyphicon glyphicon-remove removerecipe' style='color: blue; cursor: pointer;'' length='"+curr_recipe_id+"' data-toggle='tooltip' title='Remove'></span><label for='content-field'>Step</label><input type='text' name='content[]' class='form-control'/></div>";
                $(wrapperrecipe).append(htmlrecipeField);
            }
        });
        
        $(wrapperrecipe).on("click",".removerecipe", function(e) {
            e.preventDefault();
            var recipe_id = $(this).attr("value");
            var recipe_delete_id = $(this).attr("length");
            if (recipe_id != undefined) {
              $.ajax({
                  url:"/recipe/delete/"+recipe_id,
                  success : function(data) {
                    $("form #removeBlock"+recipe_delete_id).remove();
                    curr_recipe_length--;
                  }
              });
            } else {
                var recipe_delete_id = $(this).attr("length");
                if (curr_recipe_length > 1) {
                    $("form #removeBlock"+recipe_delete_id).remove();
                    curr_recipe_length--;
                }
            }
        });
    });
  </script>
@endsection
