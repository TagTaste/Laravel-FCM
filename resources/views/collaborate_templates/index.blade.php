@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> CollaborateTemplate
            <a class="btn btn-success pull-right" href="{{ route('collaborate_templates.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($collaborate_templates->count())
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name</th> <th>Fields</th>
                            <th class="text-right">OPTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($collaborate_templates as $collaborate_template)
                            <tr>
                                <td class="text-center"><strong>{{$collaborate_template->id}}</strong></td>

                                <td>{{$collaborate_template->name}}</td> <td>{{$collaborate_template->fields}}</td>
                                
                                <td class="text-right">
                                    <a class="btn btn-xs btn-primary" href="{{ route('collaborate_templates.show', $collaborate_template->id) }}">
                                        <i class="glyphicon glyphicon-eye-open"></i> View
                                    </a>
                                    
                                    <a class="btn btn-xs btn-warning" href="{{ route('collaborate_templates.edit', $collaborate_template->id) }}">
                                        <i class="glyphicon glyphicon-edit"></i> Edit
                                    </a>

                                    <form action="{{ route('collaborate_templates.destroy', $collaborate_template->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete? Are you sure?');">
                                        {{csrf_field()}}
                                        <input type="hidden" name="_method" value="DELETE">

                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $collaborate_templates->render() !!}
            @else
                <h3 class="text-center alert alert-info">Empty!</h3>
            @endif

        </div>
    </div>

@endsection