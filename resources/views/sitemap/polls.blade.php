<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($polls as $poll_id)
        <url>
            <loc>{{ Config::get('app.url') }}/polling/{{ $poll_id }}</loc>
        </url>
    @endforeach
</urlset>