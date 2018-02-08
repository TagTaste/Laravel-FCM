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
        
        $this->model = $sharedModel->getPreviewContent();
        
        return $this->sendResponse();
        
    }

    private function getDeeplinkURL($data)
    {
        $client = new \GuzzleHttp\Client();
        $res = $client->request('POST', 'https://api.branch.io/v1/url', [
            'json' => [
                "branch_key" => env('BRANCH_KEY'),

                "data" => [
                    '$canonical_identifier' => 'content/123',
                    '$og_title' => $data['ogTitle'],
                    '$og_description' => $data['ogDescription'],
                    '$og_image_url' => $data[''],
                    '$desktop_url' => 'https://play.google.com/store/apps/details?id=com.tagtaste.android',
                ],
            ],
        ]);
    }
}
