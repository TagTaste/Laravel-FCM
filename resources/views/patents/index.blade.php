@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> Patents
            <a class="btn btn-success pull-right" href="{{ route('patents.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($patents->count())
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>TITLE</th>
                        <th>DESCRIPTION</th>
                        <th>NUMBER</th>
                        <th>ISSUED_BY</th>
                        <th>AWARDED_ON</th>
                        <th>COMPANY_ID</th>
                        <th>COMPANY_ID</th>
                            <th class="text-right">OPTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($patents as $patent)
                            <tr>
                                <td>{{$patent->id}}</td>
                                <td>{{$patent->title}}</td>
                    <td>{{$patent->description}}</td>
                    <td>{{$patent->number}}</td>
                    <td>{{$patent->issued_by}}</td>
                    <td>{{$patent->awarded_on}}</td>
                    <td>{{$patent->company_id}}</td>
                    <td>{{$patent->company_id}}</td>
                                <td class="text-right">
                                    <a class="btn btn-xs btn-primary" href="{{ route('patents.show', $patent->id) }}"><i class="glyphicon glyphicon-eye-open"></i> View</a>
                                    <a class="btn btn-xs btn-warning" href="{{ route('patents.edit', $patent->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                                    <form action="{{ route('patents.destroy', $patent->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $patents->render() !!}
            @else
                <h3 class="text-center alert alert-info">Empty!</h3>
            @endif

        </div>
    </div>

@endsection