@php
    $profileValue = $profile->get($attribute->id);
    $name = "attributes[$attribute->id]";
    $inputValue = null;
    if($profileValue){
      if($attribute->inputType("text") || $attribute->inputType("textarea")){

        $profileValue = $profileValue->first();

        if($profileValue){
          $name = "profile[{$profileValue->id}]";
          $inputValue = $profileValue->value;
        }
      } else {
        $profileValue = $profileValue->keyBy('value_id');
      }

    }
@endphp

{{ $attribute->getFormInput($name,$profileValue,$inputValue) }}
