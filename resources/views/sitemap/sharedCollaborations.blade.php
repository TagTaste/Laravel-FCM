<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($sharedCollaborations as $sharedCollaboration)
        <url>
            <loc>{{ Config::get('app.url') }}/shared/{{ $sharedCollaboration->id }}/collaborations/{{$sharedCollaboration->collaborate_id}}</loc>
        </url>
    @endforeach
</urlset>