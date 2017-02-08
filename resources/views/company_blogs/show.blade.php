@extends('layout')
@section('header')
<div class="page-header">
        <h1>Company_blogs / Show #{{$company_blog->id}}</h1>
        <form action="{{ route('company_blogs.destroy', $company_blog->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('company_blogs.edit', $company_blog->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                <button type="submit" class="btn btn-danger">Delete <i class="glyphicon glyphicon-trash"></i></button>
            </div>
        </form>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <form action="#">
                <div class="form-group">
                    <label for="nome">ID</label>
                    <p class="form-control-static"></p>
                </div>
                <div class="form-group">
                     <label for="name">NAME</label>
                     <p class="form-control-static">{{$company_blog->name}}</p>
                </div>
                    <div class="form-group">
                     <label for="url">URL</label>
                     <p class="form-control-static">{{$company_blog->url}}</p>
                </div>
                    <div class="form-group">
                     <label for="company_id">COMPANY_ID</label>
                     <p class="form-control-static">{{$company_blog->company_id}}</p>
                </div>
                    <div class="form-group">
                     <label for="company_id">COMPANY_ID</label>
                     <p class="form-control-static">{{$company_blog->company_id}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('company_blogs.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection