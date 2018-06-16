<?php

namespace App\Listeners\ElasticSearch;

use App\Events\Searchable;
use App\SearchClient;
use App\Events\LogRecord;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Elasticsearch\ClientBuilder;
use Request;
use App\Version;

class Record implements ShouldQueue
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
     * @param  LogRecord  $event
     * @return void
     */
    public function handle(LogRecord $event)
    {
        $data = $event->data;

        //Add time stamp
        $data["time"]=date("h:i:s a", time());
        $data["date"]=date("Y-m-d", time());
        
        //parameters for elasticsearch
        $params = [
            'body' => $data,
            'index' => 'laravellogtest3',
            'type' => 'user_log',
        ];
        //dd($params);
        $client =  SearchClient::get();
        $client->index($params);
    }
}
