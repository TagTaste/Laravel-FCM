<?php

namespace App\Listeners;

use App\Events\ContentAnalysisEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;


class ContentAnalysisListener implements ShouldQueue
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

    public $data,$local_storage,$paramsCollection,$meta_data;
    public $content_analysis_req_collection ;

    /**
     * Handle the event.
     *
     * @param  ContentAnalysisEvent  $event
     * @return void
     */
    public function handle(ContentAnalysisEvent $event)
    {
        
        if (true) {
            $this->release(30);
        }
        $this->data = $event->data;
        $this->local_storage = \Storage::disk('s3ContentAnalysis');
        $this->paramsCollection = collect();
        $this->data->each(function($valP,$keyP){
            $key = $valP["type"];
            $val = $valP["value"];
            switch ($key) {
                case 'meta' :
                    $this->meta_data = $val;
                    break;
                default :
                    $tempArray = [];
                    $tempArray["type"] = $key."";
                    $tempArray["value"] = $val;
                    $this->paramsCollection->push($tempArray);
            }
        });
       
        $this->contentAnalysisCurlRequest($this->paramsCollection->toArray(),$this->meta_data);
    }

    private function contentAnalysisCurlRequest($data, $meta_data)
    {

        
        $post_body = [
            "meta_data" => $meta_data,
            "data" => $data,
            "bucket_name" => env("S3_CONTENT_ANALYSIS_BUCKET")
        ];


        $http_header = [
            'Content-Type' => 'application/json'
        ];
        
        /**
         * URL of the aws lambda function to be executed for content analysis
         */
        $ch = curl_init(env("CONTENT_ANALYSIS_LAMBDA_URI"));
        
        // Set so curl_exec returns the result instead of outputting it.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Follow redirect
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($ch, CURLOPT_POST,  1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_body));

        curl_setopt($ch, CURLOPT_HTTPHEADER, $http_header);

        curl_exec($ch);
        
        curl_close($ch);
        
    }
}
