    @extends('layout')

    @section('header')
        <div class="page-header clearfix">
            <h1>
                <i class="glyphicon glyphicon-align-justify"></i> Profiles
                <a class="btn btn-success pull-right" href="{{ route('profiles.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
            </h1>

        </div>
    @endsection

    @section('content')
        <div class="row">
            <div class="col-md-12">
                @if($profiles->count())
                    <table class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>USER_ID</th>
                            <th>ATTRIBUTE</th>
                            <th>VALUE</th>
                            <th>TYPE</th>
                                <th class="text-right">OPTIONS</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($profiles as $profile)

                                <tr>
                                    <td>{{$profile->id}} {{ $profile->attribute->requires_upload}}</td>
                                    <td>{{$profile->user_id}}</td>
                        <td>{{$profile->attribute->label}}</td>
                        @if($profile->attribute->requires_upload == 1)
                            <td><a href="{{ route("profile.fileDownload",$profile->value) }}">View File</a></td>
                        @else
                            <td>{{$profile->value}}</td>
                        @endif
                        <td>{{$profile->type->type}}</td>
                                    <td class="text-right">
                                        <a class="btn btn-xs btn-primary" href="{{ route('profiles.show', $profile->id) }}"><i class="glyphicon glyphicon-eye-open"></i> View</a>
                                        <a class="btn btn-xs btn-warning" href="{{ route('profiles.edit', $profile->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                                        <form action="{{ route('profiles.destroy', $profile->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $profiles->render() !!}
                @else
                   
                @endif

            </div>
            <div class="col-md-12">
                <ul>
                        @foreach($profileTypes as $type)
                        <li><a href="{{ route('profile.form',$type)}}">{{$type->type}}</a></li>
                        @endforeach
                   </ul>
            </div>
        </div>

    @endsection