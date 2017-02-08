@extends('layout')
@section('header')
<div class="page-header">
        <h1>Education / Show #{{$education->id}}</h1>
        <form action="{{ route('education.destroy', $education->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('education.edit', $education->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
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
                     <label for="degree">DEGREE</label>
                     <p class="form-control-static">{{$education->degree}}</p>
                </div>
                    <div class="form-group">
                     <label for="college">COLLEGE</label>
                     <p class="form-control-static">{{$education->college}}</p>
                </div>
                    <div class="form-group">
                     <label for="field">FIELD</label>
                     <p class="form-control-static">{{$education->field}}</p>
                </div>
                    <div class="form-group">
                     <label for="grade">GRADE</label>
                     <p class="form-control-static">{{$education->grade}}</p>
                </div>
                    <div class="form-group">
                     <label for="percentage">PERCENTAGE</label>
                     <p class="form-control-static">{{$education->percentage}}</p>
                </div>
                    <div class="form-group">
                     <label for="description">DESCRIPTION</label>
                     <p class="form-control-static">{{$education->description}}</p>
                </div>
                    <div class="form-group">
                     <label for="start_date">START_DATE</label>
                     <p class="form-control-static">{{$education->start_date}}</p>
                </div>
                    <div class="form-group">
                     <label for="end_date">END_DATE</label>
                     <p class="form-control-static">{{$education->end_date}}</p>
                </div>
                    <div class="form-group">
                     <label for="ongoing">ONGOING</label>
                     <p class="form-control-static">{{$education->ongoing}}</p>
                </div>
                    <div class="form-group">
                     <label for="profile_id">PROFILE_ID</label>
                     <p class="form-control-static">{{$education->profile_id}}</p>
                </div>
                    <div class="form-group">
                     <label for="profile_id">PROFILE_ID</label>
                     <p class="form-control-static">{{$education->profile_id}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('education.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection