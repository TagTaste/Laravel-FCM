<?php

namespace App\Http\Controllers\Api;

use App\SearchClient;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    //index = db
    //type = table
    //document = row
    //field = column

    public function search(Request $request, $type = null)
    {
        $query = $request->input('q');
        $params = [
            'index' => "api",
            'body' => [
                'query' => [
                    'query_string' => [
                        'query' => $query
                    ]
                ]
            ]
        ];
        if($request->has('type')){
            $params['type'] = $request->input('type');
        }
        $client = SearchClient::get();
    
        $response = $client->search($params);
        $this->model = [];
        if($response['hits']['total'] > 0){
            $hits = collect($response['hits']['hits']);
            $hits = $hits->groupBy("_type");
            
            foreach($hits as $name => $hit){
                $class = "\App\\$name";
                $model = $class::whereIn('id',$hit->pluck('_id'))->get()->toArray();
                $this->model[$name] = $model;
            }
            return $this->sendResponse();
    
        }
        return $this->sendResponse("Nothing found.");
    }
    
    public function suggest(Request $request, $type)
    {
        $name = $request->input('description');
        $params = [
            'index' => 'api',
            'type' => $type,
            'body' => [
            
                'suggest'=> [
                    'namesuggestion' => [
                        'text' => $name,
                        'term' => [
                            'field' => 'description'
                        ]
                    ]
                ]
            ]
        ];
        
        $client = SearchClient::get();
    
        $response = $client->search($params);
    
        return response()->json($response);
    }
}
