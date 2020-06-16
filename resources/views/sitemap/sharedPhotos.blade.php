<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($sharedPhotos as $sharedPhoto)
        <url>
            <loc>{{ Config::get('app.url') }}/shared/{{ $sharedPhoto->id }}/photo/{{$sharedPhoto->photo_id}}</loc>
        </url>
    @endforeach
</urlset>