<style>
    .page-break {
        page-break-after: always;
    }
</style>
@foreach($data as $header)
    <h1>{{$header['headerName']}}</h1>
    @foreach($header['data'] as $datum)
        <h2>{{$datum['title']}}</h2>
        $question =
    @endforeach
    @if ($loop->last)
        Do nothing
    @else
        <div class="page-break"></div>
    @endif
@endforeach