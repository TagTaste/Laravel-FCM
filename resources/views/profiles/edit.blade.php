@extends('layout')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css"
          rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> Profiles / Edit </h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            {{ Form::open(['url'=>route('profiles.updateIndividual'),'method'=>'post', 'files'=>true]) }}
                {{ Form::token() }}
                {{ Form::hidden('typeId',$typeId) }}
                @foreach($profileAttributes as $attribute)

                    @include('profiles.single')

                    @if($attribute->children)

                        <div class="row">
                            <div class="col-md-11 col-md-push-1">
                                @foreach($attribute->children as $child)
                                    @include('profiles.single',['attribute'=>$child])
                                @endforeach
                            </div>
                        </div>
                    @endif

                @endforeach

                {{ Form::bsSubmit('Save') }}
            {{ Form::close() }}

        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        $('.date-picker').datepicker({});
    </script>
@endsection
