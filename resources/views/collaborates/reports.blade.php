<style>
    .page-break {
        page-break-after: always;
    }
    .pr-answer-row {
        display: block;
        white-space: nowrap;
        width: 100%;
        margin-top: 7px;
        margin-bottom: 7px;
    }
    .pr-answer-row .pr-answer-container {
        width: 65%;
        border: 2px solid rgba(217,34,42,.4);
        height: 30px;
        border-radius: 5px;
        position: relative;
        overflow: hidden;
        background: #fbe9ea;
        margin: 0 13px 0 0;
        display: inline-block;
        vertical-align: top;
        left: 0;
    }
    .pr-text-margin {
        margin-bottom: 10px;
    }
    .pr-answer-row .pr-answer-container .active {
        height: 30px;
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
        height: 30px;
        line-height: 25px;
        z-index: 10;
        position: relative;
        padding-left: 25px;
    }
    .pr-answer-row .pr-responses-count {
        display: inline-block;
        width: 28%;
        vertical-align: middle;
        text-align: center;
        margin-left: 10px;
        vertical-align: top;
        height: 0;
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
        font-size: 13px;
        font-weight: 500;
        color: #70080c;
        margin-bottom: 0;
        font-family: Helvetica Neue, Arial;
    }

    .pr-report-pill-text--small {
        font-size: 12px;
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
    .pr-textual-intensity-container {
        margin-bottom: 10px;
    }
    .pr-reports-textual-intensity {
        width: 100%;
        display: block;
        vertical-align: top;
        margin-bottom: 5px;
    }
    .textual-intensity-text-container {
        display: inline-block;
        width: 30%;
        vertical-align: top;
        position: relative;
        height: 0;
        top: 0;
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
        width: 50%;
        display: inline-block;
        position: relative;
        height: 12px;
        background-color: rgba(251,232,233,.4);
        border-radius: 7px;
    }
    .textual-intensity-active-bar {
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        background-color: #ffa9ae;
        position: absolute;
        height: 12px;
        border-radius: 7px;
    }
    .textual-intensity-count {
        width: 15%;
        margin-top: -12px;
        margin-left: 20px;
        display: inline-block;
        position: relative;
        height: 0;
        text-align: left;
        top: 0;
    }
    .pr-intensity-numeric-block {
        margin-right: 30px;
    }
    .pr-intensity-numeric-img {
        margin-top: 5px;
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
                    $nestedQuestions = (isset($questions->questions)) ? $questions->questions : [];
                @endphp
                <p class="pr-report-pill-helper">({{$answersCount}} {{$subTitleSuffix}})</p>
                <div class="pr-report-sub-question">
                    @foreach($nestedAnswers as $nestedAnswer)
                        @php
                            $nestedQuestion = array_values(array_filter(
                                $nestedQuestions,
                                function($e) use ($nestedAnswer) {
                                    return($e->id == $nestedAnswer['question_id']);
                                    }
                                ));
                            $nestedQuestion = (count($nestedQuestion) > 0) ? $nestedQuestion[0] : null;
                            $answers = (isset($nestedAnswer['answer'])) ? $nestedAnswer['answer'] : [];
                            $totalApplicants = (isset($nestedAnswer['total_applicants'])) ? $nestedAnswer['total_applicants'] : 0;
                            $totalAnswers = (isset($nestedAnswer['total_answers'])) ? $nestedAnswer['total_answers'] : 0;
                            $nestedQuestionIndex = $loop->index + 1;
                            $isComment = (isset($nestedQuestion) && isset($nestedQuestion->selected_type)) ? $nestedQuestion->selected_type == 3 : false;
                        @endphp
                        <div>
                            <p class="pr-report-pill-title">{{$currentQuestionIndex}}.{{$nestedQuestionIndex}} {{$nestedAnswer['title']}}</p>
                            <p class="pr-report-pill-helper pr-text-margin">
                                {{$totalAnswers}} out of {{$totalApplicants}} answered this question.
                                @if ($isComment == true)
                                    (Please check the website for all the comments)
                                @endif
                            </p>
                        </div>
                        @if ($isComment == false)
                            @foreach($answers as $answer)
                                @php
                                    $answerTitle = $answer->value;
                                    $answerTotal = $answer->total;
                                    $percent = $totalAnswers === 0 ? 0 : floor($answerTotal/$totalApplicants*100);
                                    $responseTextSuffix = $answerTotal === 1 ? ' Response' : ' Responses';
                                    $percentToShow = number_format($percent, 1);
                                    $isIntensity = isset($answer->is_intensity) ? $answer->is_intensity : null;
                                    $intensityType = isset($answer->intensity_type) ? $answer->intensity_type : null;
                                @endphp
                                @if (isset($isIntensity) && $isIntensity == 1)
                                    @if ($intensityType == 1)
                                        @include('collaborates.reports-numeric-intensity', ['currentAnswer' => $answer, 'totalAnswers' => $totalApplicants])
                                    @elseif($intensityType == 2)
                                        @include('collaborates.reports-textual-intensity', ['currentAnswer' => $answer, 'totalAnswers' => $totalApplicants])
                                    @endif
                                @else
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
                                @endif
                            @endforeach
                        @endif

                    @endforeach
                </div>
            @else
                {{-- If not nested questions --}}
                @php
                    $totalAnswers = $headerData['total_answers'];
                    $totalApplicants = $headerData['total_applicants'];
                    $isComment = $questions->select_type == 3;
                    $answers = isset($headerData['answer']) ? $headerData['answer'] : [];
                @endphp
                <p class="pr-report-pill-helper pr-text-margin">
                    {{$totalAnswers}} out of {{$totalApplicants}} answered this question.
                    @if ($isComment == true)
                        (Please check the website for all the comments)
                    @endif
                </p>
                @if ($isComment == false)
                    @foreach ($answers as $answer)
                        @php
                            $answerTitle = $answer->value;
                            $answerTotal = $answer->total;
                            $percent = $totalAnswers == 0 ? 0 : floor($answerTotal/$totalApplicants*100);
                            $responseTextSuffix = $answerTotal === 1 ? ' Response' : ' Responses';
                            $percentToShow = number_format($percent, 1);
                            $isIntensity = isset($answer->is_intensity) ? $answer->is_intensity : null;
                            $intensityType = isset($answer->intensity_type) ? $answer->intensity_type : null;
                        @endphp
                        @if (isset($isIntensity) && $isIntensity == 1)
                            @if ($intensityType == 1)
                                @include('collaborates.reports-numeric-intensity', ['currentAnswer' => $answer, 'totalAnswers' => $totalApplicants])
                            @elseif($intensityType == 2)
                                @include('collaborates.reports-textual-intensity', ['currentAnswer' => $answer, 'totalAnswers' => $totalApplicants])
                            @endif
                        @else
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
                        @endif
                    @endforeach
                @endif
            @endif
        </div>
    @endforeach
    @if ($loop->last)
    @else
        <div class="page-break"></div>
    @endif
@endforeach