<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($sharedProducts as $sharedProduct)
        <url>
            <loc>{{ Config::get('app.url') }}/shared/{{ $sharedProduct->id }}/product/{{$sharedProduct->product_id}}</loc>
        </url>
    @endforeach
</urlset>