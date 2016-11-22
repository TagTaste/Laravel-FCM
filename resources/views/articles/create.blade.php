@extends('layout')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
<div class="page-header">
  <h1><i class="glyphicon glyphicon-plus"></i> Articles / Create New {{ ucfirst($type) }}</h1>
</div>
@endsection

@section('content')
@include('error')

<div class="row">
  <div class="col-md-12">

    <form action="{{ route('articles.store') }}" method="POST">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <input type="hidden" name="type" value="{{$type}}">
      <div class="col-md-9">
        @if($requiresTitle)
        <div class="form-group @if($errors->has('title')) has-error @endif">
         <label for="title-field">Title</label>
         <input type="text" id="title-field" name="article[title]" class="form-control" value="{{ old("title") }}"/>
         @if($errors->has("title"))
          <span class="help-block">{{ $errors->first("title") }}</span>
          @endif
       </div>
       @endif
       @include($type . "_articles.forms.create")
     </div>
     <div class="col-md-3">
      <div class="form-group @if($errors->has('privacy_id')) has-error @endif">
       <label for="privacy_id-field">Privacy</label>
       <select name="article[privacy_id]" id="" class='form-control'>
          @foreach($privacy as $name => $id)
            <option value="{{ $id }}" @if($id == 1) selecterd @endif> {{ $name }} </option>
          @endforeach
       </select>
       @if($errors->has("privacy_id"))
       <span class="help-block">{{ $errors->first("privacy_id") }}</span>
       @endif
     </div>
     <div class="form-group @if($errors->has('status')) has-error @endif">
       <label for="status-field">Status</label>
       <input type="text" id="status-field" name="article[status]" class="form-control" value="{{ old("status") }}"/>
       @if($errors->has("status"))
       <span class="help-block">{{ $errors->first("status") }}</span>
       @endif
     </div>
     <div class="form-group @if($errors->has('template_id')) has-error @endif">
       <label for="template_id-field">Template</label>

       <select name="article[template_id]" id="" class="form-control">
         @foreach($templates as $name => $id)
            <option value="{{ $id }}">{{ $name }}</option>
         @endforeach
       </select>
       @if($errors->has("template_id"))
       <span class="help-block">{{ $errors->first("template_id") }}</span>
       @endif
     </div>

     <div class="form-group @if($errors->has('comments_enabled')) has-error @endif">
       <label for="comments_enabled-field">Comments Enabled</label>
       <div class="btn-group" data-toggle="buttons"><label class="btn btn-primary"><input type="radio" value="1" name="article[comments_enabled]" id="comments_enabled-field" autocomplete="off"> True</label><label class="btn btn-primary active"><input type="radio" name="article[comments_enabled]" value="0" id="comments_enabled-field" autocomplete="off"> False</label></div>
       @if($errors->has("comments_enabled"))
       <span class="help-block">{{ $errors->first("comments_enabled") }}</span>
       @endif
     </div>

     <div class="well well-sm">
      <button type="submit" class="btn btn-primary">Create</button>
      <a class="btn btn-link pull-right" href="{{ route('articles.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
    </div>
  </div>




     {{--  <!-- <div class="form-group @if($errors->has('author_id')) has-error @endif">
     <label for="author_id-field">Author_id</label>
     <input type="text" id="author_id-field" name="author_id" class="form-control" value="{{ old("author_id") }}"/>
     @if($errors->has("author_id"))
     <span class="help-block">{{ $errors->first("author_id") }}</span>
     @endif
   </div> --> --}}

   
   
   
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
