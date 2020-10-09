<?php


namespace App\Traits;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\SearchClient;

trait HashtagFactory
{

    public function createHashtag($hashtags, $modelName, $modelId)
    {
        foreach($hashtags as $hashtag) {
            $hash = $this->hashtagExist($hashtag);
            if(!$hash['hits']['total']) {
                $model = new \App\Hashtag();  
                $model->id =Carbon::now()->timestamp;          
                $model->tag=$hashtag;
                $model->public_use = [$modelName.'\''.$modelId];
                $model->created=Carbon::now()->timestamp; 
                $model->updated=Carbon::now()->timestamp; 
            } else {
                $hashDocument = $hash['hits']['hits'][0]['_source'];
                $public_use = $hashDocument['public_use'];
                array_unshift($public_use,$modelName.'\''.$modelId);
                $hashDocument['public_use'] = $public_use;
                $hashDocument['updated'] = Carbon::now()->timestamp; 
                $model = new \App\Hashtag($hashDocument);
            }
            \App\Documents\Hashtag::create($model);
            sleep(3);
        }
    }
    public function deleteExistingHashtag($modelName, $modelId)
    {
        $document = $this->getDocumentContainingModel($modelName.'\''.$modelId);
        if($document['hits']['total']['value']) {
            foreach($document['hits']['hits'] as $hit) {
                if(count($hit['_source']['public_use']) == 1) {
                    $model = new \App\Hashtag($hit['_source']);
                    \App\Documents\Hashtag::delete($model);
                }  else {
                    $doc = $hit['_source'];
                    $index = array_search($modelName.'\''.$modelId,$doc['public_use']);
                    unset($doc['public_use'][$index]);
                    $doc['public_use'] = array_values($doc['public_use']);
                    $model = new \App\Hashtag($doc);
                    \App\Documents\Hashtag::create($model);
                    sleep(5);
                }
            }
        }
    }

    protected function hashtagExist($hashtag) {
        $params = [
            'index' => "api",
            'body' => [
                "from" => 0, "size" => 1000,
                'query' => [
                    'query_string' => [
                        'query' => $hashtag,
                        'fields' => ['tag']
                         ]
                    ]
                ]
            ];
            $params['type'] = 'hashtag';
        $client = SearchClient::get();
        $response = $client->search($params);
        return $response;
    }
        
    protected function getDocumentContainingModel($model)
    {
        $params = [
            'index' => "api",
            'body' => [
                "from" => 0, "size" => 1000,
                'query' => [
                    'query_string' => [
                        'query' => $model
                         ]
                    ]
            ]];
        $params['type'] = 'hashtag';
        $client = SearchClient::get();
        $response = $client->search($params);
        return $response;
    }
    
    public function trendingHashtags()
    {
        $params = [
            'index' => "api",
            'body' => [
                "from" => 0, "size" => 20,
                
                    'query'=> [
                    'match_all'=> [ 'boost' => 1.2 ]
                    ] ,  
                    'sort'=> [
                        ['updated'=>['order'=>'desc']]
                      ]
            ]];
        $params['type'] = 'hashtag';
        $client = SearchClient::get();
        $response = $client->search($params);
        if($response['hits']['total']== 0){
            return null;
        } else {
            $response = $response['hits']['hits'];
            $tag = [];
            foreach($response as $tags) {
                $tag[] = [
                    'tag'=>$tags['_source']['tag'],
                    'count'=>count($tags['_source']['public_use'])
                ];
            }
            return $tag;
        }
        
    }

    public function hashtagSuggestions($key) 
    {
        $params = [
            'index' => "api",
            'body' => [
                "from" => 0, "size" => 1000,
                'query'=>[
                    'query_string' => [
                        'query' => $key,
                            'fields'=>['tag']
                         ]
                ],
                'suggest' => [
                    'my-suggestion-1'=> [
                            'text'=> $key,
                            'term'=> [
                                 'field'=> 'tag'
                            ]
                    ]
                            ]
            ]];
            $params['type'] = 'hashtag';
            $client = SearchClient::get();
            $response = $client->search($params);
            $suggestions = $response['suggest']['my-suggestion-1'][0]['options'];
            if(count($suggestions) == 0){
                return null;
            } else {
                $response = $suggestions;
                $tag = [];
                foreach($response as $tags) {
                    $tag[] = [
                        'tag'=>$tags['text']
                    ];
                }
                
            }
            if($response['hits']['total'] != 0){
                $response = $response['hits']['hits'];
                foreach($response as $tags) {
                $tag[] = [
                    'tag'=>$tags['_source']['tag']
                ];
            }   
            }
            return $tag;
    }

    public function getModelsForFeed($key)
    {
        $params = [
            'index' => "api",
            'body' => [
                "from" => 0, "size" => 1000,
                'query' => [
                    'query_string' => [
                        'query' => $key,
                            'fields'=>['tag']
                         ]
                    ]
            ]];
        $params['type'] = 'hashtag';
        $client = SearchClient::get();
        $response = $client->search($params);
        if($response['hits']['total'] == 0){
            return null;
        }
        $response = $response['hits']['hits'][0]['_source']['public_use'];
        return $response;
    }

}