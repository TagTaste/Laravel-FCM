@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> Experiences
            <a class="btn btn-success pull-right" href="{{ route('experiences.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($experiences->count())
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>COMPANY</th>
                        <th>DESIGNATION</th>
                        <th>DESCRIPTION</th>
                        <th>LOCATION</th>
                        <th>start_date</th>
                        <th>END_DATE</th>
                        <th>CURRENT_COMPANY</th>
                        <th>PROFILE_ID</th>
                        <th>PROFILE_ID</th>
                            <th class="text-right">OPTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($experiences as $experience)
                            <tr>
                                <td>{{$experience->id}}</td>
                                <td>{{$experience->company}}</td>
                    <td>{{$experience->designation}}</td>
                    <td>{{$experience->description}}</td>
                    <td>{{$experience->location}}</td>
                    <td>{{$experience->start_date}}</td>
                    <td>{{$experience->end_date}}</td>
                    <td>{{$experience->current_company}}</td>
                    <td>{{$experience->profile_id}}</td>
                    <td>{{$experience->profile_id}}</td>
                                <td class="text-right">
                                    <a class="btn btn-xs btn-primary" href="{{ route('experiences.show', $experience->id) }}"><i class="glyphicon glyphicon-eye-open"></i> View</a>
                                    <a class="btn btn-xs btn-warning" href="{{ route('experiences.edit', $experience->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                                    <form action="{{ route('experiences.destroy', $experience->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $experiences->render() !!}
            @else
                <h3 class="text-center alert alert-info">Empty!</h3>
            @endif

        </div>
    </div>

@endsection