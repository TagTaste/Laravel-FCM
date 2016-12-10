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

            <form action="{{ route('profiles.updateIndividual') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="typeId" value="{{ $typeId }}">
                @foreach($profileAttributes as $attribute)
                    @php
                        $value = $profile->get($attribute->id);
                        $name = "attributes[$attribute->id]";
                        $inputValue = null;
                        if($value){
                          if($attribute->inputType("text") || $attribute->inputType("textarea")){
                            $v = $value->first();

                                if($v){
                                  $name = "profile[{$v->id}]";
                                  $inputValue = $v->value;
                                }


                          } else {
                            $value = $value->keyBy('value_id');
                          }

                        }
                    @endphp
                    <div class="form-group">
                        <label> {{ $attribute->label}}</label>

                        @if($attribute->inputType("text"))

                            <input class="form-control" type="text" name="{{ $name }}" value="{{$inputValue}}"/>

                        @elseif($attribute->inputType("textarea"))
                            <textarea class="form-control" name="{{ $name }}" id="" cols="30"
                                      rows="10">{{$inputValue}}</textarea>
                        @elseif($attribute->inputType('file'))
                            <input type="file" name="{{ $name }}" id="">
                        @elseif($attribute->inputType("checkbox"))

                            @foreach($attribute->values as $attributeValue)
                                @php
                                    if($value){
                                      $profileValue = $value->get($attributeValue->id);
                                    }

                                    $checked = null;
                                    $name = "attributes[{$attribute->id}][value_id][]";

                                    if(isset($profileValue)) {
                                      $checked = "checked";
                                      $name = "profile[{$profileValue->id}][value_id][]";
                                    }
                                @endphp

                                <input name="{{ $name }}" type="checkbox"
                                       value="{{ $attributeValue->id }}" {{$checked}}>


                                {{ $attributeValue->name }} <br/>

                            @endforeach
                        @endif
                    </div>
                @endforeach
                <div class="form-group">
                    <input class="btn btn-primary" type="submit" value="Save">
                </div>
            </form>

        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        $('.date-picker').datepicker({});
    </script>
@endsection
