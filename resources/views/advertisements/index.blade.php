@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> Advertisements
            <a class="btn btn-success pull-right" href="{{ route('advertisements.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($advertisements->count())
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>TITLE</th>
                        <th>DESCRIPTION</th>
                        <th>YOUTUBE_URL</th>
                        <th>VIDEO</th>
                        <th>COMPANY_ID</th>
                        <th>COMPANY_ID</th>
                            <th class="text-right">OPTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($advertisements as $advertisement)
                            <tr>
                                <td>{{$advertisement->id}}</td>
                                <td>{{$advertisement->title}}</td>
                    <td>{{$advertisement->description}}</td>
                    <td>{{$advertisement->youtube_url}}</td>
                    <td>{{$advertisement->video}}</td>
                    <td>{{$advertisement->company_id}}</td>
                    <td>{{$advertisement->company_id}}</td>
                                <td class="text-right">
                                    <a class="btn btn-xs btn-primary" href="{{ route('advertisements.show', $advertisement->id) }}"><i class="glyphicon glyphicon-eye-open"></i> View</a>
                                    <a class="btn btn-xs btn-warning" href="{{ route('advertisements.edit', $advertisement->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                                    <form action="{{ route('advertisements.destroy', $advertisement->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $advertisements->render() !!}
            @else
                <h3 class="text-center alert alert-info">Empty!</h3>
            @endif

        </div>
    </div>

@endsection