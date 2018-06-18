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
use Carbon\Carbon;

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
        $now = Carbon::now();
        $data["time"] = $now->format('H:i:s');
        $data["date"] = $now->format('Y-m-d');
        
        //parameters for elasticsearch
        //$month = date('F', strtotime($data["date"]));
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
