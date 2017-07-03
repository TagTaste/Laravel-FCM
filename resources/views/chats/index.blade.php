@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> Chat
            <a class="btn btn-success pull-right" href="{{ route('chats.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($chats->count())
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name</th> <th>Profile_id</th>
                            <th class="text-right">OPTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($chats as $chat)
                            <tr>
                                <td class="text-center"><strong>{{$chat->id}}</strong></td>

                                <td>{{$chat->name}}</td> <td>{{$chat->profile_id}}</td>
                                
                                <td class="text-right">
                                    <a class="btn btn-xs btn-primary" href="{{ route('chats.show', $chat->id) }}">
                                        <i class="glyphicon glyphicon-eye-open"></i> View
                                    </a>
                                    
                                    <a class="btn btn-xs btn-warning" href="{{ route('chats.edit', $chat->id) }}">
                                        <i class="glyphicon glyphicon-edit"></i> Edit
                                    </a>

                                    <form action="{{ route('chats.destroy', $chat->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete? Are you sure?');">
                                        {{csrf_field()}}
                                        <input type="hidden" name="_method" value="DELETE">

                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $chats->render() !!}
            @else
                <h3 class="text-center alert alert-info">Empty!</h3>
            @endif

        </div>
    </div>

@endsection