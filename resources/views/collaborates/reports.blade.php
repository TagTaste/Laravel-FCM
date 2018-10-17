<style>
    .page-break {
        page-break-after: always;
    }
</style>
@foreach($data as $header)
    <h1>{{$header['headerName']}}</h1>
    @foreach($header['data'] as $datum)
        <h2>{{$datum['title']}}</h2>
        @php
            $questions = $datum['question'];
            $questions1 = (isset($questions->questions)) ? $questions->questions : [];
        @endphp
        @foreach($questions1 as $question)
            <p>{{$question->title}}</p>
        @endforeach
    @endforeach
    @if ($loop->last)
    @else
        <div class="page-break"></div>
    @endif
@endforeach