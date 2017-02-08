@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> Education
            <a class="btn btn-success pull-right" href="{{ route('education.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($education->count())
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>DEGREE</th>
                        <th>COLLEGE</th>
                        <th>FIELD</th>
                        <th>GRADE</th>
                        <th>PERCENTAGE</th>
                        <th>DESCRIPTION</th>
                        <th>START_DATE</th>
                        <th>END_DATE</th>
                        <th>ONGOING</th>
                        <th>PROFILE_ID</th>
                        <th>PROFILE_ID</th>
                            <th class="text-right">OPTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($education as $education)
                            <tr>
                                <td>{{$education->id}}</td>
                                <td>{{$education->degree}}</td>
                    <td>{{$education->college}}</td>
                    <td>{{$education->field}}</td>
                    <td>{{$education->grade}}</td>
                    <td>{{$education->percentage}}</td>
                    <td>{{$education->description}}</td>
                    <td>{{$education->start_date}}</td>
                    <td>{{$education->end_date}}</td>
                    <td>{{$education->ongoing}}</td>
                    <td>{{$education->profile_id}}</td>
                    <td>{{$education->profile_id}}</td>
                                <td class="text-right">
                                    <a class="btn btn-xs btn-primary" href="{{ route('education.show', $education->id) }}"><i class="glyphicon glyphicon-eye-open"></i> View</a>
                                    <a class="btn btn-xs btn-warning" href="{{ route('education.edit', $education->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                                    <form action="{{ route('education.destroy', $education->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $education->render() !!}
            @else
                <h3 class="text-center alert alert-info">Empty!</h3>
            @endif

        </div>
    </div>

@endsection