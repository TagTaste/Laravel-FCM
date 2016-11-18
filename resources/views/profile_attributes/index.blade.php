@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> ProfileAttributes
            <a class="btn btn-success pull-right" href="{{ route('profile_attributes.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($profile_attributes->count())
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NAME</th>
                        <th>LABEL</th>
                        <th>DESCRIPTION</th>
                        <th>USER_ID</th>
                        <th>MULTILINE</th>
                        <th>REQUIRES_UPLOAD</th>
                        <th>ALLOWED_MIME_TYPES</th>
                        <th>ENABLED</th>
                        <th>REQUIRED</th>
                        <th>PARENT_ID</th>
                        <th>TEMPLATE_ID</th>
                            <th class="text-right">OPTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($profile_attributes as $profile_attribute)
                            <tr>
                                <td>{{$profile_attribute->id}}</td>
                                <td>{{$profile_attribute->name}}</td>
                    <td>{{$profile_attribute->label}}</td>
                    <td>{{$profile_attribute->description}}</td>
                    <td>{{$profile_attribute->user_id}}</td>
                    <td>{{$profile_attribute->multiline}}</td>
                    <td>{{$profile_attribute->requires_upload}}</td>
                    <td>{{$profile_attribute->allowed_mime_types}}</td>
                    <td>{{$profile_attribute->enabled}}</td>
                    <td>{{$profile_attribute->required}}</td>
                    <td>{{$profile_attribute->parent_id}}</td>
                    <td>{{$profile_attribute->template_id}}</td>
                                <td class="text-right">
                                    <a class="btn btn-xs btn-primary" href="{{ route('profile_attributes.show', $profile_attribute->id) }}"><i class="glyphicon glyphicon-eye-open"></i> View</a>
                                    <a class="btn btn-xs btn-warning" href="{{ route('profile_attributes.edit', $profile_attribute->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                                    <form action="{{ route('profile_attributes.destroy', $profile_attribute->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $profile_attributes->render() !!}
            @else
                <h3 class="text-center alert alert-info">Empty!</h3>
            @endif

        </div>
    </div>

@endsection