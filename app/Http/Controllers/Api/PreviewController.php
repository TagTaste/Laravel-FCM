<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class PreviewController extends Controller
{
    private function getModel(&$modelName, &$id)
    {
        $class = "\\App\\" . ucwords($modelName);
        return $class::find($id);
    }
    
    public function show(Request $request,$modelName,$modelId)
    {
        $sharedModel = $this->getModel($modelName, $modelId);
        
        if (!$sharedModel) {
            return $this->sendError("Nothing found for given Id.");
        }

        $data = $sharedModel->getPreviewContent();
        $deeplink = $this->getDeeplinkURL($data, $modelName,$modelId);

        $res = [
            'title' => $data['ogTitle'],
            'image' => $data['ogImage'],
            'description' => $data['ogDescription'],
            'type' => 'article',
            'url' => $data['redirectUrl'],
            'site_name' => 'TagTaste',
            'deeplink' => $deeplink->url,
            'modelID' => $modelId,
            'model' => ucwords($modelName),

        ];

        $this->model = $res;
        
        return $this->sendResponse();
        
    }

    private function getDeeplinkURL($data, $modelName,$modelId)
    {
        $client = new \GuzzleHttp\Client();
        $res = $client->request('POST', 'https://api.branch.io/v1/url', [
            'json' => [
                "branch_key" => env('BRANCH_KEY'),

                "data" => [
                    '$canonical_identifier' => 'share_feed/'.$data['modelId'],
                    '$og_title' => $data['ogTitle'],
                    '$og_description' => $data['ogDescription'],
                    '$og_image_url' => $data['ogImage'],
//                    '$og_image_width' => '273px',
//                    '$og_image_height' => '526px',
                    '$og_type' => 'article',
                    '$og_app_id' => env('FACEBOOK_ID'),
                    '$desktop_url' => $data['redirectUrl'],

                    '$twitter_card' => $data['cardType'],
                    '$twitter_title' => $data['ogTitle'],
                    '$twitter_description' => $data['ogDescription'],
                    '$twitter_image_url' => $data['ogImage'],
                    '$twitter_site' => '@tagtaste',

                    'typeID' => $modelId,
                    'type' => ucwords($modelName),
                    'profileID' => $data['owner'],

                ],
            ],
        ]);
        \Log::info('Deeplink: '.$res->getBody());
        return json_decode((string)$res->getBody());
    }
}
