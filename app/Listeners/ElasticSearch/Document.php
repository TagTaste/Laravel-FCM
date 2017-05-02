<?php

namespace App\Listeners\ElasticSearch;

use App\Events\Searchable;
use Elasticsearch\ClientBuilder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Document
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Searchable  $event
     * @return void
     */
    public function handle(Searchable $event)
    {
        $client =  ClientBuilder::create()->build();
        $client->index($event->document->toArray());
    }
}
