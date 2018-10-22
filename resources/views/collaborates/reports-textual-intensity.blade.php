@php
    $currentAnswers = [];
@endphp
@foreach($answers as $answer)
    @php
        $answerObj = array_filter(
            $currentAnswers,
            function($e) use ($answer) {
                $e->leaf_id == $answer->leaf_id;
            }
        );
        if (count($answerObj) > 0) {
            $answerObj[0]->first->finalTotal += $answer->total;
            $answerObj[0]->selectedIntensity[$answer->intensity] += $answer->intensity ? $answer->total : 0;
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
        @endphp
        <div class="pr-answer-row">
            <div class="pr-answer-container">
                <div class="active" style="width: {{$percent}}%;"></div>
                <div class="answer-pill-details">
                    <p class="pr-report-pill-text">
                        @isset($path)
                            <span>{{$path}}</span>
                        @endisset
                        {{$answerTitle}}
                    </p>
                </div>
            </div>
            <div class="pr-responses-count">
                <p class="pr-report-helper-text">{{$answerTotal}} {{$responseTextSuffix}} ({{$percentToShow}}%)</p>
            </div>
        </div>
        @foreach($intensities as $intensity => $value)
            @php
                $intensityPercent = round(($value/$finalTotal)*100)
            @endphp
            <div class="pr-reports-textual-intensity">
                <div class="textual-intensity-text-container">
                    <p class="textual-intensity-text">{{$intensity}}</p>
                </div>
                <div class="textual-intensity-bar">
                    <p style="width: {{$intensityPercent}}%;" class="textual-intensity-active-bar"></p>
                </div>
                <div class="textual-intensity-count">
                    <p class="textual-intensity-text">{{$value}}</p>
                </div>
            </div>
        @endforeach
    @endforeach
@endif
