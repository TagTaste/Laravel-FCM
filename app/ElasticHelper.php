<?php

namespace App;

use App\SearchClient;

class ElasticHelper
{
    public static function suggestedSearch($query, $type = null,$isWildCard,$suggest)
    {    
        if($isWildCard) {
            $query = $query.'*';
        } else {
            $query = $query;
        }

        switch($type){
            case "company":
                $fields = ['name^3','cuisines^1','speciality^1','about^2'];
                break;
            case "profile":
                $fields = ['name^3','about^2','handle^1'];
                break;
            case "product";
                $fields = ['name^3','productCategory^1','subCategory^1', 'brand_name^2', 'company_name^2'];
                break;
            case "collaborate":
                $fields  = ['title^3','keywords^2'];
                break;  
            default:
                $fields = ['name^3','title^3','brand_name^2','company_name^2','handle^1','keywords^2','productCategory','subCategory'];
        }

        $params = [];

        if($suggest) {
            $params = [
            'index' => "api",
            'body' => [
                "from" => 0, "size" => 1000,
                'query' => [
                    'query_string' => [
                        'query' => $query,
                        'fields' => $fields
                         ]
                    ],  
                    'suggest' => [
                        'my-suggestion-1'=> [
                                'text'=> $query,
                                'term'=> [
                                     'field'=> 'name'
                                ]
                        ],
                        'my-suggestion-2'=> [
                                'text'=> $query,
                                'term'=> [
                                     'field'=> 'title'
                                ]
                        ]
                    ]
                ]
            ];
        } else {
            $params = [
                'index' => "api",
                'body' => [
                    "from" => 0, "size" => 1000,
                    'query' => [
                        'query_string' => [
                            'query' => $query,
                            'fields' => $fields
                             ]
                        ]
                    ]
                ];
        }
        $client = SearchClient::get();
        $response = $client->search($params);

        return $response;
    }
}
