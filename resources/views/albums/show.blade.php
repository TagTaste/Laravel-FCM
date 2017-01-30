@extends('layout')
@section('header')
<div class="page-header">
        <h1>Albums / Show #{{$album->id}}</h1>
        <form action="{{ route('albums.destroy', $album->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('albums.edit', $album->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                <button type="submit" class="btn btn-danger">Delete <i class="glyphicon glyphicon-trash"></i></button>
            </div>
        </form>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">

            <form action="#">
                <div class="form-group">
                    <label for="nome">ID</label>
                    <p class="form-control-static"></p>
                </div>
                <div class="form-group">
                     <label for="name">NAME</label>
                     <p class="form-control-static">{{$album->name}}</p>
                </div>
                    <div class="form-group">
                     <label for="description">DESCRIPTION</label>
                     <p class="form-control-static">{{$album->description}}</p>
                </div>


            </form>

            <a class="btn btn-link" href="{{ route('albums.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
        <div class="col-md-8">
            @if($album->photos->count())
                <ul class="list-unstyled">
                    @foreach($album->photos as $photo)
                        <li>
                            <img src="/photos/{{$photo->id}}.jpg" alt="" height="auto" width="100px">
                            <p><small>{{ $photo->caption }}</small></p>
                        <form class="photo-tag-form" action="{{ route("photos.tag") }}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="photo_id" value="{{ $photo->id }}">
                            {{ Form::select('tagbook_id',$tagboard,null,['class'=>'btn btn-xs btn-default add-to-ideabook']) }}

                        </form>
                        </li>
                    @endforeach
                </ul>

            @endif
        </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        $("document").ready(function(){
            var ideabookBtn = $(".add-to-ideabook");
            ideabookBtn.on('change',function(e){
                var el = $(this);
                var tagBook = parseInt(el.val());
                if(tagBook != 0){
                    var form = el.closest('form');
                    form.submit();
                }
            });

            var forms = $(".photo-tag-form");

            forms.on('submit',function(e){
                e.preventDefault();
                var el = $(this);
                var url = el.attr('action');
                $.post(url,el.serialize(),function(data){
                    console.log(data);
                });
            });
        });
    </script>
@endsection