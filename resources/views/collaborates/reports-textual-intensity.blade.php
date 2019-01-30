@foreach($answers as $currentAnswer)
    @php
        $path = isset($currentAnswer->path) ? $currentAnswer->path : null;
        $answerTitle = $currentAnswer->value;
        $intensities = $currentAnswer->intensity;
        $finalTotal = $currentAnswer->total;
        $prResponseSuffix = $finalTotal == 1 ? 'Response' : 'Responses';
        $percent = round(($currentAnswer->total/$totalAnswers)*100);
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
        @foreach($intensities as $intensity)
            @php
                $intensityPercent = round(($intensity['count']/$finalTotal)*100)
            @endphp
            <div class="pr-reports-textual-intensity">
                <div class="textual-intensity-text-container textual-intensity-text">
                    {{$intensity['value']}}
                </div>
                <div class="textual-intensity-bar">
                    <div style="width: {{$intensityPercent}}%;" class="textual-intensity-active-bar"></div>
                </div>
                <div class="textual-intensity-count textual-intensity-text">
                    {{$intensity['count']}}
                </div>
            </div>
        @endforeach
    </div>
@endforeach