@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> ProfileShows
            <a class="btn btn-success pull-right" href="{{ route('profile_shows.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($profile_shows->count())
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>TITLE</th>
                        <th>DESCRIPTION</th>
                        <th>CHANNEL</th>
                        <th>CURRENT</th>
                        <th>START_DATE</th>
                        <th>END_DATE</th>
                        <th>URL</th>
                        <th>APPEARED_AS</th>
                        <th>PROFILE_ID</th>
                            <th class="text-right">OPTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($profile_shows as $profile_show)
                            <tr>
                                <td>{{$profile_show->id}}</td>
                                <td>{{$profile_show->title}}</td>
                    <td>{{$profile_show->description}}</td>
                    <td>{{$profile_show->channel}}</td>
                    <td>{{$profile_show->current}}</td>
                    <td>{{$profile_show->start_date}}</td>
                    <td>{{$profile_show->end_date}}</td>
                    <td>{{$profile_show->url}}</td>
                    <td>{{$profile_show->appeared_as}}</td>
                    <td>{{$profile_show->profile_id}}</td>
                                <td class="text-right">
                                    <a class="btn btn-xs btn-primary" href="{{ route('profile_shows.show', $profile_show->id) }}"><i class="glyphicon glyphicon-eye-open"></i> View</a>
                                    <a class="btn btn-xs btn-warning" href="{{ route('profile_shows.edit', $profile_show->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                                    <form action="{{ route('profile_shows.destroy', $profile_show->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $profile_shows->render() !!}
            @else
                <h3 class="text-center alert alert-info">Empty!</h3>
            @endif

        </div>
    </div>

@endsection