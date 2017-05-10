<?php namespace App;

use Elasticsearch\ClientBuilder;

class SearchClient
{
    public static function get()
    {
        return ClientBuilder::create()->setHosts([env('ELASTICSEARCH_HOST')])->build();
    }
}