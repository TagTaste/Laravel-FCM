<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($profiles as $handle)
        <url>
            <loc>{{ Config::get('app.url') }}/{{ $handle }}</loc>
        </url>
    @endforeach
</urlset>