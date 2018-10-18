@php
    $currentAnswers = [];
@endphp
@foreach($answers as $answer)
    $answerObj = array_filter(
        $currentAnswers,
        function($e) {
            $e->leaf_id == $answer->leaf_id;
        }
    );
    @php

    @endphp
@endforeach