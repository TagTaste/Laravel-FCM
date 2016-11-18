@extends('layout')
@section('header')
<div class="page-header">
        <h1>Templates / Show #{{$template->id}}</h1>
        <form action="{{ route('templates.destroy', $template->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('templates.edit', $template->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
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
                     <p class="form-control-static">{{$template->name}}</p>
                </div>
                    <div class="form-group">
                     <label for="view">VIEW</label>
                     <p class="form-control-static">{{$template->view}}</p>
                </div>
                    <div class="form-group">
                     <label for="enabled">ENABLED</label>
                     <p class="form-control-static">{{$template->enabled}}</p>
                </div>
                    <div class="form-group">
                     <label for="template_type_id">TEMPLATE_TYPE_ID</label>
                     <p class="form-control-static">{{$template->template_type_id}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('templates.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection