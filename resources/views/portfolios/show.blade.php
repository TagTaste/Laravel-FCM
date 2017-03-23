@extends('layout')
@section('header')
<div class="page-header">
        <h1>Portfolios / Show #{{$portfolio->id}}</h1>
        <form action="{{ route('portfolios.destroy', $portfolio->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('portfolios.edit', $portfolio->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
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
                     <label for="worked_for">WORKED_FOR</label>
                     <p class="form-control-static">{{$portfolio->worked_for}}</p>
                </div>
                    <div class="form-group">
                     <label for="description">DESCRIPTION</label>
                     <p class="form-control-static">{{$portfolio->description}}</p>
                </div>
                    <div class="form-group">
                     <label for="company_id">COMPANY_ID</label>
                     <p class="form-control-static">{{$portfolio->company_id}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('portfolios.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection