{{--@foreach($answers as $currentAnswer)--}}
    @php
        $path = isset($currentAnswer->path) ? $currentAnswer->path : null;
        $answerTitle = $currentAnswer->value;
        $finalTotal = $currentAnswer->total;
        $avgIntensity = 0;
        foreach($currentAnswer->intensity as $intensity) {
            $avgIntensity += $intensity['value'] * $intensity['count'];
        }
        $avgIntensity = round($avgIntensity, 1);
        $prResponseSuffix = $finalTotal == 1 ? 'Response' : 'Responses';
        $percent = number_format(floor(($currentAnswer->total/$totalAnswers) *100), 1);
    @endphp
    <div class="pr-answer-row">
        <div class="pr-answer-container">
            <div class="active" style="width: {{$percent}}%;"></div>
            <div class="answer-pill-details">
                <p class="pr-report-pill-text">
                    @isset($avgIntensity)
                        <span class="pr-intensity-numeric-block">
                            <img class="pr-intensity-numeric-img" src="https://www.tagtaste.com/images/product-review/icons-intensity-bars.png" />
                            {{$avgIntensity}}
                        </span>
                    @endisset
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
{{--@endforeach--}}