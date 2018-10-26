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
            $answerObj[0]->finalTotal += $answer->total;
            if (isset($answerObj[0]->selectedIntensity[$answer->intensity])) {
                $answerObj[0]->selectedIntensity[$answer->intensity] += $answer->intensity ? $answer->total : 0;
            } else {
                $answerObj[0]->selectedIntensity[$answer->intensity] = $answer->intensity ? $answer->total : 0;
            }
            $answerObj[0]->percent = round(($answerObj[0]->finalTotal/$totalAnswers)*100);
        } else {
            $tempAnswer = clone $answer;
            $tempAnswer->finalTotal = $answer->total;
            $tempAnswer->selectedIntensity[$answer->intensity] = $answer->intensity ? $answer->total : 0;
            $tempAnswer->percent = round(($answer->total/$totalAnswers)*100);
            array_push($currentAnswers,$tempAnswer);
        }
    @endphp
@endforeach
@if (count($currentAnswers) > 0)
    @foreach($currentAnswers as $currentAnswer)
        @php
            $path = isset($currentAnswer->path) ? $currentAnswer->path : null   ;
            $answerTitle = $currentAnswer->value;
            $percent = $currentAnswer->percent;
            $intensities = $currentAnswer->selectedIntensity;
            $finalTotal = $currentAnswer->finalTotal;
            $prResponseSuffix = $finalTotal == 1 ? 'Response' : 'Responses';
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
                <p class="pr-report-helper-text">{{$finalTotal}} {{$prResponseSuffix}} ({{$percent}}%)</p>
            </div>
        </div>
        <div class="pr-textual-intensity-container">
            @foreach($intensities as $intensity => $value)
                @php
                    $intensityPercent = round(($value/$finalTotal)*100)
                @endphp
                <div class="pr-reports-textual-intensity">
                    <div class="textual-intensity-text-container textual-intensity-text">
                        {{$intensity}}
                    </div>
                    <div class="textual-intensity-bar">
                        <div style="width: {{$intensityPercent}}%;" class="textual-intensity-active-bar"></div>
                    </div>
                    <div class="textual-intensity-count textual-intensity-text">
                        {{$value}}
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
@endif