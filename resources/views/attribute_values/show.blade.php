@extends('layout')
@section('header')
<div class="page-header">
        <h1>AttributeValues / Show #{{$attribute_value->id}}</h1>
        <form action="{{ route('attribute_values.destroy', $attribute_value->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('attribute_values.edit', $attribute_value->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
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
                     <p class="form-control-static">{{$attribute_value->name}}</p>
                </div>
                    <div class="form-group">
                     <label for="value">VALUE</label>
                     <p class="form-control-static">{{$attribute_value->value}}</p>
                </div>
                    <div class="form-group">
                     <label for="default">DEFAULT</label>
                     <p class="form-control-static">{{$attribute_value->default}}</p>
                </div>
                    <div class="form-group">
                     <label for="attribute_id">ATTRIBUTE_ID</label>
                     <p class="form-control-static">{{$attribute_value->attribute_id}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('attribute_values.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection