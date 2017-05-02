<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class SearchController extends Controller
{

    //index = db
    //type = table
    //document = row
    //field = column

    public function search(Request $request, $type)
    {
        if($type !== 'profiles'){
            //since we currently only support profile searching
            return;
        }
        
        if(!$request->has('name')){
            //since we currently search searching based on name.
            return;
        }
        
        $match = $request->only('name');
        
        $params = [
            'index' => "users",
            'type' => $type,
            'body' => [
                'query' => [
                    'match' => $match
                ]
            ]
        ];

        $client = \Elasticsearch\ClientBuilder::create()->build();
    
        $response = $client->search($params);

        return response()->json($response);
    }
    
    public function suggest(Request $request, $type)
    {
        $name = $request->input('name');
        $params = [
            'index' => 'users',
            'type' => $type,
            'body' => [
            
                'suggest'=> [
                    'namesuggestion' => [
                        'text' => $name,
                        'term' => [
                            'field' => 'name'
                        ]
                    ]
                ]
            ]
        ];
        
        $client = \Elasticsearch\ClientBuilder::create()->build();
    
        $response = $client->search($params);
    
        return response()->json($response);
    }
}
