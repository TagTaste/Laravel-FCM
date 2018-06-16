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
use Torann\GeoIP\Facades\GeoIP;

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
        /* if( $data["platform"] == "Web" )
        {
            $agent = new Agent();
            $agent->setUserAgent($data["device"]);
            if($agent->isDesktop())$data["device"] = "PC Web";
            else if($agent->isPhone())$data["device"] = "Mobile Web";
            //get browser info
            if($agent->browser())$data["browser"] = $agent->browser();
            if($agent->browser())$data["browser_version"] = $agent->version($agent->browser());
        } */

        //Add time stamp
        $data["time"]=date("h:i:s a", time());
        $data["date"]=date("Y-m-d", time());
        
        //To get location info
        $locationObj = geoip($data["ip"]);
        //$locationObj = geoip("42.105.255.100");
        $data["countryCode"] = $locationObj->country;
        $data["state"] = $locationObj->state;
        $data["city"] = $locationObj->city;
        
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
