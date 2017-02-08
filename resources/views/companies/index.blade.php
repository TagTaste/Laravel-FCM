@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> Companies
            <a class="btn btn-success pull-right" href="{{ route('companies.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($companies->count())
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NAME</th>
                        <th>ABOUT</th>
                        <th>LOGO</th>
                        <th>HERO_IMAGE</th>
                        <th>PHONE</th>
                        <th>EMAIL</th>
                        <th>REGISTERED_ADDRESS</th>
                        <th>ESTABLISHED_ON</th>
                        <th>STATUS_ID</th>
                        <th>STATUS_ID</th>
                        <th>TYPE</th>
                        <th>TYPE</th>
                        <th>EMPLOYEE_COUNT</th>
                        <th>CLIENT_COUNT</th>
                        <th>ANNUAL_REVENUE_START</th>
                        <th>ANNUAL_REVENUE_END</th>
                        <th>FACEBOOK_URL</th>
                        <th>TWITTER_URL</th>
                        <th>LINKEDIN_URL</th>
                        <th>INSTAGRAM_URL</th>
                        <th>YOUTUBE_URL</th>
                        <th>PINTEREST_URL</th>
                        <th>GOOGLE_PLUS_URL</th>
                        <th>USER_ID</th>
                        <th>USER_ID</th>
                            <th class="text-right">OPTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($companies as $company)
                            <tr>
                                <td>{{$company->id}}</td>
                                <td>{{$company->name}}</td>
                    <td>{{$company->about}}</td>
                    <td>{{$company->logo}}</td>
                    <td>{{$company->hero_image}}</td>
                    <td>{{$company->phone}}</td>
                    <td>{{$company->email}}</td>
                    <td>{{$company->registered_address}}</td>
                    <td>{{$company->established_on}}</td>
                    <td>{{$company->status_id}}</td>
                    <td>{{$company->status_id}}</td>
                    <td>{{$company->type}}</td>
                    <td>{{$company->type}}</td>
                    <td>{{$company->employee_count}}</td>
                    <td>{{$company->client_count}}</td>
                    <td>{{$company->annual_revenue_start}}</td>
                    <td>{{$company->annual_revenue_end}}</td>
                    <td>{{$company->facebook_url}}</td>
                    <td>{{$company->twitter_url}}</td>
                    <td>{{$company->linkedin_url}}</td>
                    <td>{{$company->instagram_url}}</td>
                    <td>{{$company->youtube_url}}</td>
                    <td>{{$company->pinterest_url}}</td>
                    <td>{{$company->google_plus_url}}</td>
                    <td>{{$company->user_id}}</td>
                    <td>{{$company->user_id}}</td>
                                <td class="text-right">
                                    <a class="btn btn-xs btn-primary" href="{{ route('companies.show', $company->id) }}"><i class="glyphicon glyphicon-eye-open"></i> View</a>
                                    <a class="btn btn-xs btn-warning" href="{{ route('companies.edit', $company->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                                    <form action="{{ route('companies.destroy', $company->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $companies->render() !!}
            @else
                <h3 class="text-center alert alert-info">Empty!</h3>
            @endif

        </div>
    </div>

@endsection