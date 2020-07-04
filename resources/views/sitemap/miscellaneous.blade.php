<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<url>
        <loc>{{ Config::get('app.url') }}</loc>
    </url>
    <url>
        <loc>{{ Config::get('app.url') }}/login</loc>
    </url>
    <url>
        <loc>{{ Config::get('app.url') }}/register</loc>
    </url>
    <url>
        <loc>{{ Config::get('app.url') }}/password/forgot</loc>
    </url>
    <url>
        <loc>{{ Config::get('app.url') }}/career</loc>
    </url>
    <url>
        <loc>{{ Config::get('app.url') }}/privacy</loc>
    </url>
    <url>
        <loc>{{ Config::get('app.url') }}/terms</loc>
    </url>
</urlset>