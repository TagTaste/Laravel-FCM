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
            'index' => "users",
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

        return response()->json($response);
    }
    
    public function suggest(Request $request, $type)
    {
        $name = $request->input('description');
        $params = [
            'index' => 'users',
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
