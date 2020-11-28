<?php

namespace App\Console\Commands\Build\Search;

use Illuminate\Console\Command;
use App\SearchClient;
use App\Jobs\StoreElasticModel;

class UniqueHashtag extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:search:hashtag:unique';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $params = [
            'index' => "api",
            'body' => [
                "from" => 0, "size" => 1000,
                'query' => [
                    'query_string' => [
                        'query' => '*',
                            'fields'=>['tag']
                         ]
                    ]
            ]];
        $params['type'] = 'hashtag';
        $client = SearchClient::get();
        $response = $client->search($params);
        foreach($response['hits']['hits'] as $hit) {
            $publicuse = $this->getSimilarHashtags($hit['_source']['tag']);
            $model = new \App\Hashtag();  
                $model->id =mt_rand(1000000000, 9999999999);          
                $model->tag=$hit['_source']['tag'];
                $model->public_use = $publicuse;
                $model->updated= $hit['_source']['updated']; 
                $model->created= $hit['_source']['created'];
                echo(json_encode($model));
                $job = (new StoreElasticModel($model));
                dispatch($job);
        }
    }

    protected function getSimilarHashtags($tag)
    {
        $params = [
            'index' => "api",
            'body' => [
                "from" => 0, "size" => 1000,
                'query' => [
                    'query_string' => [
                        'query' => $tag,
                            'fields'=>['tag']
                         ]
                    ]
            ]];
        $params['type'] = 'hashtag';
        $client = SearchClient::get();
        $response = $client->search($params);
        $publicUse = [];
        foreach($response['hits']['hits'] as $hit) {
            $publicUse = array_merge($publicUse,$hit['_source']['public_use']);
            $model = new \App\Hashtag($hit['_source']);
            \App\Documents\Hashtag::delete($model);
        }
        return $publicUse;
    }
}
