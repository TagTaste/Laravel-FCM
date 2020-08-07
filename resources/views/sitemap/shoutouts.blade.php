<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($shoutouts as $shoutout_id)
        <url>
            <loc>{{ Config::get('app.url') }}/shoutout/{{ $shoutout_id }}</loc>
        </url>
    @endforeach
</urlset>