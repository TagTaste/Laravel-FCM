@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> JobType
            <a class="btn btn-success pull-right" href="{{ route('job_types.create') }}"><i
                        class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($job_types->count())
                <table class="table table-condensed table-striped">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th class="text-right">OPTIONS</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($job_types as $job_type)
                        <tr>
                            <td class="text-center"><strong>{{$job_type->id}}</strong></td>

                            <td>{{$job_type->name}}</td>
                            <td>{{$job_type->description}}</td>

                            <td class="text-right">
                                <a class="btn btn-xs btn-primary" href="{{ route('job_types.show', $job_type->id) }}">
                                    <i class="glyphicon glyphicon-eye-open"></i> View
                                </a>

                                <a class="btn btn-xs btn-warning" href="{{ route('job_types.edit', $job_type->id) }}">
                                    <i class="glyphicon glyphicon-edit"></i> Edit
                                </a>

                                <form action="{{ route('job_types.destroy', $job_type->id) }}" method="POST"
                                      style="display: inline;" onsubmit="return confirm('Delete? Are you sure?');">
                                    {{csrf_field()}}
                                    <input type="hidden" name="_method" value="DELETE">

                                    <button type="submit" class="btn btn-xs btn-danger"><i
                                                class="glyphicon glyphicon-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! $job_types->render() !!}
            @else
                <h3 class="text-center alert alert-info">Empty!</h3>
            @endif

        </div>
    </div>

@endsection