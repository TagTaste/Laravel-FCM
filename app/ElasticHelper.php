<?php

namespace App;

use App\SearchClient;

class ElasticHelper
{
    public static function suggestedSearch($query, $type = null, $isWildCard, $suggest)
    {
        if ($isWildCard) {
            $query = $query . '*';
        } else {
            $query = $query;
        }
        
        switch($type){
            case "company":
                $fields = ['name^3', 'cuisines^1', 'speciality^1', 'about^2'];
                break;
            case "profile":
                $fields = ['name^3', 'handle^2'];
                break;
            case "product";
                $fields = ['name^3', 'productCategory^1', 'subCategory^1', 'brand_name^2', 'company_name^2'];
                break;
            case "collaborate":
                $fields  = ['title^3', 'keywords^2'];
                break;
            case "surveys":
                $fields  = ['title^3'];
                break;
            case "quiz":
                $fields  = ['title^3'];
                break;
            default:
                $fields = ['name^3', 'title^3', 'brand_name^2', 'company_name^2', 'handle^1', 'keywords^2', 'productCategory', 'subCategory'];
        }

        $params = [];

        $suggestWithFields = [
            'text' => $query,
            'term' => [
                'field' => 'name'
            ]
        ];
        if ($suggest) {
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
                        'my-suggestion-1' => [
                            'text' => $query,
                            'term' => [
                                'field' => 'title'
                            ]
                        ]
                    ]
                ]
            ];
            if ($type != 'quiz' && $type != "surveys" && $type != "collaborate") {
                $params['body']['suggest']['my-suggestion-2'] = $suggestWithFields;
            }
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
        if ($type) {
            $params['type'] = $type;
            if ($type == "product-review") {
                $params['type'] = "collaborate";
            } else if ($type == "polls") {
                $params['type'] = "polling";
            }
        }
        // dd($params);
        $client = SearchClient::get();
        $response = $client->search($params);

        return $response;
    }
}
