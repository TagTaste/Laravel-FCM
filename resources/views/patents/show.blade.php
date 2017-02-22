@extends('layout')
@section('header')
<div class="page-header">
        <h1>Patents / Show #{{$patent->id}}</h1>
        <form action="{{ route('patents.destroy', $patent->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('patents.edit', $patent->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
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
                     <label for="title">TITLE</label>
                     <p class="form-control-static">{{$patent->title}}</p>
                </div>
                    <div class="form-group">
                     <label for="description">DESCRIPTION</label>
                     <p class="form-control-static">{{$patent->description}}</p>
                </div>
                    <div class="form-group">
                     <label for="number">NUMBER</label>
                     <p class="form-control-static">{{$patent->number}}</p>
                </div>
                    <div class="form-group">
                     <label for="issued_by">ISSUED_BY</label>
                     <p class="form-control-static">{{$patent->issued_by}}</p>
                </div>
                    <div class="form-group">
                     <label for="awarded_on">AWARDED_ON</label>
                     <p class="form-control-static">{{$patent->awarded_on}}</p>
                </div>
                    <div class="form-group">
                     <label for="company_id">COMPANY_ID</label>
                     <p class="form-control-static">{{$patent->company_id}}</p>
                </div>
                    <div class="form-group">
                     <label for="company_id">COMPANY_ID</label>
                     <p class="form-control-static">{{$patent->company_id}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('patents.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection