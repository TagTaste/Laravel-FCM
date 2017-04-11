@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> Collaborate
            <a class="btn btn-success pull-right" href="{{ route('collaborates.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($collaborates->count())
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Title</th> <th>I_am</th> <th>Looking_for</th> <th>Purpose</th> <th>Deliverables</th> <th>Who_can_help</th> <th>Expires_on</th> <th>Profile_id</th> <th>Company_id</th>
                            <th class="text-right">OPTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($collaborates as $collaborate)
                            <tr>
                                <td class="text-center"><strong>{{$collaborate->id}}</strong></td>

                                <td>{{$collaborate->title}}</td> <td>{{$collaborate->i_am}}</td> <td>{{$collaborate->looking_for}}</td> <td>{{$collaborate->purpose}}</td> <td>{{$collaborate->deliverables}}</td> <td>{{$collaborate->who_can_help}}</td> <td>{{$collaborate->expires_on}}</td> <td>{{$collaborate->profile_id}}</td> <td>{{$collaborate->company_id}}</td>
                                
                                <td class="text-right">
                                    <a class="btn btn-xs btn-primary" href="{{ route('collaborates.show', $collaborate->id) }}">
                                        <i class="glyphicon glyphicon-eye-open"></i> View
                                    </a>
                                    
                                    <a class="btn btn-xs btn-warning" href="{{ route('collaborates.edit', $collaborate->id) }}">
                                        <i class="glyphicon glyphicon-edit"></i> Edit
                                    </a>

                                    <form action="{{ route('collaborates.destroy', $collaborate->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete? Are you sure?');">
                                        {{csrf_field()}}
                                        <input type="hidden" name="_method" value="DELETE">

                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $collaborates->render() !!}
            @else
                <h3 class="text-center alert alert-info">Empty!</h3>
            @endif

        </div>
    </div>

@endsection