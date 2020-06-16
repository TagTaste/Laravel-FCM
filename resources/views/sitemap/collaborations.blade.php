<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($collaborations as $collaboration)
        <url>
        	@if($collaboration->collaborate_type == "product-review" )
            	<loc>{{ Config::get('app.url') }}/collaborations/{{ $collaboration->id }}/product-review</loc>
            @else
            	<loc>{{ Config::get('app.url') }}/collaborations/{{ $collaboration->id }}</loc>
            @endif
        </url>
    @endforeach
</urlset>