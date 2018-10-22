<style>
    .page-break {
        page-break-after: always;
    }
    .pr-answer-row {
        display: block;
    }
    .pr-answer-row .pr-answer-container {
        width: 100%;
        border: 2px solid rgba(217,34,42,.4);
        height: 38px;
        border-radius: 5px;
        position: relative;
        overflow: hidden;
        background: #fbe9ea;
        margin: 10px 13px 0 0;
    }
    .pr-answer-row .pr-answer-container .active {
        height: 100%;
        background: #f7cdce;
        width: 0;
        position: absolute;
        top: 0;
        left: 0;
        z-index: 1;
    }
    .pr-answer-row .pr-answer-container .answer-pill-details {
        display: flex;
        align-items: center;
        height: 38px;
        line-height: 30px;
        z-index: 10;
        position: relative;
        padding-left: 25px;
    }
    .pr-answer-row .pr-responses-count {
        margin: 5px 0 10px 0;
    }
    .pr-report-helper-text {
        font-size: 12px;
        font-weight: 400;
        color: #70080c;
        margin-bottom: 0;
        line-height: 14px;
        font-family: Helvetica Neue, Arial;
    }
    .pr-report-pill-text {
        font-size: 16px;
        font-weight: 500;
        color: #70080c;
        margin-bottom: 0;
        font-family: Helvetica Neue, Arial;
    }
    .pr-report-sub-question {
        padding-left: 15px;
        margin-bottom: 10px;
    }
    .pr-report-pill-title {
        font-size: 16px;
        font-weight: 500;
        color: rgba(14,1,1,.9);
        margin-bottom: 5px;
        line-height: 20px;
        font-family: Helvetica Neue, Arial;
    }
    .pr-report-pill-helper {
        font-size: 12px;
        font-weight: 400;
        color: rgba(0,0,0,.65);
        line-height: 14px;
        margin-bottom: 0;
        font-family: Helvetica Neue, Arial;
    }
    .pr-reports-textual-intensity {
        width: 100%;
        display: block;
        white-space: nowrap;
    }
    .textual-intensity-text-container {
        display: inline-block;
        width: 33%;
        vertical-align: top;
        position: relative;
        height: 12px;
    }
    .textual-intensity-text {
        font-size: 12px;
        font-weight: 500;
        line-height: 15px;
        color: rgba(112,8,12,.7);
        margin-bottom: 0;
        position: relative;
    }
    .textual-intensity-bar {
        width: 60%;
        display: inline-block;
        vertical-align: top;
        border-radius: 7px;
        position: relative;
        vertical-align: top;
    }
    .textual-intensity-active-bar {
        background-color: #ffa9ae;
        position: absolute;
        top: 0;
        height: 12px;
        left: 0;
        border-radius: 7px;
        vertical-align: top;
    }
    .textual-intensity-count {
        width: 5%;
        margin-left: 20px;
        display: inline-block;
        vertical-align: top;
        position: relative;
        height: 12px;
    }
</style>
@foreach($data as $header)
    <h1>{{$header['headerName']}}</h1>
    @foreach($header['data'] as $headerData)
        @php
            $questions = $headerData['question'];
            $currentQuestionIndex = $loop->index + 1;
        @endphp
        <div class="pr-report-question">
            <p class="pr-report-pill-title">{{ $currentQuestionIndex }}. {{$headerData['title']}}</p>
            {{--If nested question--}}
            @if (isset($headerData['nestedAnswers']))
                @php
                    $nestedAnswers = (isset($headerData['nestedAnswers'])) ? $headerData['nestedAnswers'] : [];
                    $answersCount = count($headerData['nestedAnswers']);
                    $subTitleSuffix = $answersCount === 1 ? ' sub question' : 'sub questions';
                @endphp
                <p class="pr-report-pill-helper">({{$answersCount}} {{$subTitleSuffix}})</p>
                <div class="pr-report-sub-question">
                    @foreach($nestedAnswers as $nestedAnswer)
                        @php
                            $answers = (isset($nestedAnswer['answer'])) ? $nestedAnswer['answer'] : [];
                            $totalApplicants = (isset($nestedAnswer['total_applicants'])) ? $nestedAnswer['total_applicants'] : 0;
                            $totalAnswers = (isset($nestedAnswer['total_answers'])) ? $nestedAnswer['total_answers'] : 0;
                            $nestedQuestionIndex = $loop->index + 1;
                        @endphp
                        <div>
                            <p class="pr-report-pill-title">{{$currentQuestionIndex}}.{{$nestedQuestionIndex}} {{$nestedAnswer['title']}}</p>
                            <p class="pr-report-pill-helper">{{$totalAnswers}} out of {{$totalApplicants}} answered this question.</p>
                        </div>
                        @foreach($answers as $answer)
                            @php
                                $answerTitle = $answer->value;
                                $answerTotal = $answer->total;
                                $percent = $totalAnswers === 0 ? 0 : $answerTotal/$totalAnswers*100;
                                $isIntensity = $answer->intensity;
                                $responseTextSuffix = $answerTotal === 1 ? ' Response' : ' Responses';
                                $percentToShow = number_format($percent, 1);
                            @endphp
                            <div class="pr-answer-row">
                                <div class="pr-answer-container">
                                    <div class="active" style="width: {{$percent}}%;"></div>
                                    <div class="answer-pill-details">
                                        <p class="pr-report-pill-text">
                                            {{$answerTitle}}
                                        </p>
                                    </div>
                                </div>
                                <div class="pr-responses-count">
                                    <p class="pr-report-helper-text">{{$answerTotal}} {{$responseTextSuffix}} ({{$percentToShow}}%)</p>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            @else
                {{-- If not nested questions --}}
                @php
                    $isIntensity = isset($questions->is_intensity) ? $questions->is_intensity : null;
                    $intensityType = isset($questions->intensity_type) ? $questions->intensity_type : null;
                    $totalAnswers = $headerData['total_answers'];
                    $totalApplicants = $headerData['total_applicants'];
                    $isComment = $questions->select_type == 3;
                    $answers = isset($headerData['answer']) ? $headerData['answer'] : [];
                @endphp
                <p class="pr-report-pill-helper">{{$totalAnswers}} out of {{$totalApplicants}} answered this question.</p>
                @if ($isIntensity == 0)
                    @if ($isComment === false)
                        @foreach ($answers as $answer)
                            @php
                                $answerTitle = $answer->value;
                                $answerTotal = $answer->total;
                                $percent = $totalAnswers === 0 ? 0 : $answerTotal/$totalAnswers*100;
                                $isIntensity = $answer->intensity;
                                $responseTextSuffix = $answerTotal === 1 ? ' Response' : ' Responses';
                                $percentToShow = number_format($percent, 1);
                            @endphp
                            <div class="pr-answer-row">
                                <div class="pr-answer-container">
                                    <div class="active" style="width: {{$percent}}%;"></div>
                                    <div class="answer-pill-details">
                                        <p class="pr-report-pill-text">
                                            {{$answerTitle}}
                                        </p>
                                    </div>
                                </div>
                                <div class="pr-responses-count">
                                    <p class="pr-report-helper-text">{{$answerTotal}} {{$responseTextSuffix}} ({{$percentToShow}}%)</p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                @else
                    @if ($intensityType === 1)
                        @include('collaborates.reports-numeric-intensity')
                    @else
                        @include('collaborates.reports-textual-intensity', ['answers' => $answers, 'totalAnswers' => $totalAnswers])
                    @endif
                @endif
            @endif
        </div>
    @endforeach
    @if ($loop->last)
    @else
        <div class="page-break"></div>
    @endif
@endforeach