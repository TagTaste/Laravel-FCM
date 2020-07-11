<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @if(@isset($publicReviewProduct))
        <sitemap>
            <loc>{{ Config::get('app.url') }}/sitemap-products.xml</loc>
        </sitemap>      
    @endif
    @if(@isset($profile))
        <sitemap>
            <loc>{{ Config::get('app.url') }}/sitemap-profiles.xml</loc>
        </sitemap>
    @endif
    @if(@isset($company))
        <sitemap>
            <loc>{{ Config::get('app.url') }}/sitemap-companies.xml</loc>
        </sitemap>
    @endif
    @if(@isset($shoutout))
        <sitemap>
            <loc>{{ Config::get('app.url') }}/sitemap-shoutout.xml</loc>
        </sitemap>
    @endif
    @if(@isset($photo))
        <sitemap>
            <loc>{{ Config::get('app.url') }}/sitemap-photos.xml</loc>
        </sitemap>
    @endif
    @if(@isset($collaborate))
        <sitemap>
            <loc>{{ Config::get('app.url') }}/sitemap-collaborations.xml</loc>
        </sitemap>
    @endif
    @if(@isset($polling))
        <sitemap>
            <loc>{{ Config::get('app.url') }}/sitemap-polling.xml</loc>
        </sitemap>
    @endif
    @if(@isset($shareablePhoto))
        <sitemap>
            <loc>{{ Config::get('app.url') }}/sitemap-shared-photos.xml</loc>
        </sitemap>
    @endif
    @if(@isset($shareableCollaborate))
        <sitemap>
            <loc>{{ Config::get('app.url') }}/sitemap-shared-collaborations.xml</loc>
        </sitemap>
    @endif
    @if(@isset($shareableProduct))
        <sitemap>
            <loc>{{ Config::get('app.url') }}/sitemap-shared-products.xml</loc>
        </sitemap>
    @endif
    @if(@isset($shareablePolling))
        <sitemap>
            <loc>{{ Config::get('app.url') }}/sitemap-shared-pollings.xml</loc>
        </sitemap>
    @endif
    @if(@isset($shareableShoutout))
        <sitemap>
            <loc>{{ Config::get('app.url') }}/sitemap-shared-shoutout.xml</loc>
        </sitemap>
    @endif
    <sitemap>
        <loc>{{ Config::get('app.url') }}/sitemap-miscellaneous.xml</loc>
    </sitemap>

</sitemapindex>