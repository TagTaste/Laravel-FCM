<?php

namespace App\Listeners;

use App\Events\ContentAnalysisEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

class ContentAnalysisListener 
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
      
        $this->data = $event->data;
        $this->local_storage = \Storage::disk('s3ContentAnalysis');
        $this->paramsCollection = collect();
        $this->data->each(function($valP,$keyP){
            $key = $valP["type"];
            $val = $valP["value"];
            switch ($key) {
                case 'image':
                    $dump_path = $this->local_storage->putFile('temp', $val,'public');
                    $tempArray = [];
                    $tempArray["type"] = $key."";
                    $tempArray["value"] = $dump_path;
                    $this->paramsCollection->push($tempArray);
                    # code...
                    break;
                case 'video':
                    $dump_path = $this->local_storage->putFile('temp', $val,'public');
                    $tempArray = [];
                    $tempArray["type"] = $key."";
                    $tempArray["value"] = $dump_path;
                    $this->paramsCollection->push($tempArray);
                    # code...
                    break;
                case 'text':
                    $tempArray = [];
                    $tempArray["type"] = $key."";
                    $tempArray["value"] = $val;
                    $this->paramsCollection->push($tempArray);
                    # code...
                    break;
                case 'meta' :
                    $this->meta_data = $val;
                    break;
                default :
                    \Log::info("No match!");
            }
        });
        $this->contentAnalysisCurlRequest($this->paramsCollection->toArray(),$this->meta_data);
    }

    private function contentAnalysisCurlRequest($data, $meta_data)
    {

        
        $post_body = [
            "meta_data" => $meta_data,
            "data" => $data,
            "bucket_name" => "nonsafe.content.bucket"
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
        //dd($post_body);
    }
}
