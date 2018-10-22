@php
    $currentAnswers = [];
@endphp
@foreach($answers as $answer)
    @php
        $answerObj = array_values(array_filter(
            $currentAnswers,
            function($e) use ($answer) {
                return($e->leaf_id == $answer->leaf_id);
            }
        ));
        if (count($answerObj) > 0) {
            $answerObj[0]->finalTotal += (int)$answer->total;
            $answerObj[0]->currentIntensity += (int)$answer->total * (int)$answer->intensity;
            $answerObj[0]->percent = round(($answerObj[0]->finalTotal/$totalAnswers)*100);
            $answerObj[0]->avgIntensity = number_format((float)$answerObj[0]->currentIntensity/$answerObj[0]->finalTotal, 2, '.', '');
        } else {
            $tempAnswer = clone $answer;
            $tempAnswer->finalTotal = (int)$answer->total;
            $tempAnswer->currentIntensity = (int)$answer->total * (int)$answer->intensity;
            $tempAnswer->avgIntensity = number_format((float)$tempAnswer->currentIntensity/$tempAnswer->finalTotal, 2, '.', '');
            $tempAnswer->percent = round(($answer->total/$totalAnswers)*100);
            array_push($currentAnswers, $tempAnswer);
        }
    @endphp
@endforeach
@if (count($currentAnswers) > 0)
    @foreach($currentAnswers as $currentAnswer)
        @php
            $path = isset($currentAnswer->path) ? $currentAnswer->path : null;
            $answerTitle = $currentAnswer->value;
            $percent = $currentAnswer->percent;
            $finalTotal = $currentAnswer->finalTotal;
        @endphp
        <div class="pr-answer-row">
            <div class="pr-answer-container">
                <div class="active" style="width: {{$percent}}%;"></div>
                <div class="answer-pill-details">
                    <p class="pr-report-pill-text">
                        @isset($path)
                            <span>{{$path}} > </span>
                        @endisset
                        {{$answerTitle}}
                    </p>
                </div>
            </div>
            <div class="pr-responses-count">
                <p class="pr-report-helper-text">{{$finalTotal}} </p>
            </div>
        </div>
    @endforeach
@endif