<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($sharedPolls as $sharedPoll)
        <url>
            <loc>{{ Config::get('app.url') }}/shared/{{ $sharedPoll->id }}/polling/{{$sharedPoll->poll_id}}</loc>
        </url>
    @endforeach
</urlset>