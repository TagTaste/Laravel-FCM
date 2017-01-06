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
                        <input type="hidden" name="receipe_id{{$key}}" value="{{ $value['id'] }}">
                        <span class="glyphicon glyphicon-remove removeReceipe" style="color: blue; cursor: pointer;" length="{{$key+1}}" data-toggle="tooltip" title="Remove" value="{{$value['id']}}"></span>
                        <label for="content-field">Step</label>
                        <input type="text" name="content[]" class="form-control" value="{{$value->content}}" />
                    </div>
                  @endforeach
                </div>
              <div class="well well-sm">
                  <button class="btn btn-primary" type="button" id="addReceipe"><strong>Add Step</strong></button>
                  <button type="submit" class="btn btn-primary">Save</button>
                  <a class="btn btn-link pull-right" href="{{ route('articles.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
              </div>
            </form>
          @else
            No receipe added yet.
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
        var max_receipe_fields = 50;
        var wrapperReceipe = $("form #addMoreFields");
        var curr_receipe_length = $('form #addMoreFields').find('[name^="content"]').length;
        var curr_receipe_id = curr_receipe_length;
        $("form #addReceipe").click(function(e) {
            e.preventDefault();
            if(curr_receipe_length < max_receipe_fields) {
                curr_receipe_length++;
                curr_receipe_id++;
                var htmlReceipeField = "<div class='form-group' id='removeBlock"+curr_receipe_id+"'><span class='glyphicon glyphicon-remove removeReceipe' style='color: blue; cursor: pointer;'' length='"+curr_receipe_id+"' data-toggle='tooltip' title='Remove'></span><label for='content-field'>Step</label><input type='text' name='content[]' class='form-control'/></div>";
                $(wrapperReceipe).append(htmlReceipeField);
            }
        });
        
        $(wrapperReceipe).on("click",".removeReceipe", function(e) {
            e.preventDefault();
            var receipe_id = $(this).attr("value");
            var receipe_delete_id = $(this).attr("length");
            if (receipe_id != undefined) {
              $.ajax({
                  url:"/receipe/delete/"+receipe_id,
                  success : function(data) {
                    $("form #removeBlock"+receipe_delete_id).remove();
                    curr_receipe_length--;
                  }
              });
            } else {
                var receipe_delete_id = $(this).attr("length");
                if (curr_receipe_length > 1) {
                    $("form #removeBlock"+receipe_delete_id).remove();
                    curr_receipe_length--;
                }
            }
        });
    });
  </script>
@endsection
