@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> Albums
            <a class="btn btn-success pull-right" href="{{ route('albums.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($albums->count())
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NAME</th>
                            <th>DESCRIPTION</th>
                            <th class="text-right">OPTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($albums as $album)
                            <tr>
                                <td>{{$album->id}}</td>
                                <td>{{$album->name}}</td>
                                <td>{{$album->description}}</td>
                                <td class="text-right">
                                    <a class="btn btn-xs btn-primary" href="{{ route('albums.show', $album->id) }}"><i class="glyphicon glyphicon-eye-open"></i> View</a>
                                    <a class="btn btn-xs btn-warning" href="{{ route('albums.edit', $album->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                                    <form action="{{ route('albums.destroy', $album->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                    </form>
                                    <form class="album-tag-form" action="{{ route("albums.tag") }}" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="album_id" value="{{ $album->id }}">
                                        {{ Form::select('tagbook_id',$tagboard,null,['class'=>'btn btn-xs btn-default add-to-ideabook']) }}

                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $albums->render() !!}
            @else
                <h3 class="text-center alert alert-info">Empty!</h3>
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

            var forms = $(".album-tag-form");

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