<?php
/**
 * Created by PhpStorm.
 * User: aman
 * Date: 12/02/18
 * Time: 11:40 AM
 */

namespace App;


class Deeplink
{
    private $model;
    private $modelId;
    private $modelName;
    private $shared;
    private $share_id;


    public function __construct($modelName, $modelId, $shared = false, $share_id = 0)
    {
        $this->model = $this->getModel($modelName, $modelId);
        if(!$this->model)
            \Log::error("Model: $modelName does not exists");
        $this->modelId = $modelId;
        $this->modelName = $modelName;
        $this->shared = $shared;
        $this->share_id = $share_id;
    }


    public static function getShortLink($modelName, $modelId, $isShared = false, $share_id = 0)
    {
        $key = 'deeplink:'.$modelName.':'.$modelId.':'.$share_id;
        if(!\Redis::exists($key)) {
            $self = new self($modelName, $modelId, $isShared, $share_id);
            $deeplink = $self->getDeeplinkUrl();
            \Redis::set($key,json_encode($deeplink));
        }
        return (json_decode(\Redis::get($key)))->url;
    }

    public static function getLongLink($modelName, $modelId, $isShared = false, $share_id = 0)
    {
        $key = 'deeplink:'.$modelName.':'.$modelId.':'.$share_id;
//        if(\Redis::exists($key)) {
//            return (json_decode(\Redis::get($key)))->url;
//        }
        $url = 'https://tagtaste.app.link/?modelName='.$modelName.'&modelID='.$modelId.'&$fallback_url='.urlencode(Deeplink::getActualUrl($modelName, $modelId, $isShared, $share_id)).'&$canonical_identifier='.urlencode('share_feed/'.$modelId).'&shareTypeID='.$share_id.'&isShared='.$isShared;
        return $url;
    }

    private function getDeeplinkUrl()
    {
        $data = $this->model->getPreviewContent();
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

                    // type and typeID will be deprecated due to branch reserved keyword issue
                    'typeID' => $this->modelId,
                    'type' => $this->modelName,

                    'modelName' => $this->modelName,
                    'modelID' => $this->modelId,
                    'shareTypeID' => $this->share_id,
                    'isShared' => $this->shared,

                    'profileID' => $data['owner'],

                ],
            ],
        ]);
        return json_decode((string)$res->getBody());
    }

    private function getModel(&$modelName, &$id)
    {
        $class = "\\App\\" . ucwords($modelName);
        return $class::find($id);
    }

    private static function getActualUrl($modelName, $modelId, $shared = false, $share_id = 0)
    {
        if($shared) {
            return env('APP_URL')."/feed/view/share/$modelName/$share_id/$modelId";

        } else {
            switch ($modelName) {
                case 'photo':       return env('APP_URL')."/feed/view/photo/$modelId";
                case 'shoutout':    return env('APP_URL')."/feed/view/shoutout/$modelId";
                case 'collaborate': return env('APP_URL')."/collaborate/$modelId";
                case 'job':         return env('APP_URL')."/jobs/$modelId";
                case 'recipe':      return env('APP_URL')."/recipe/$modelId";
                case 'profile':     return env('APP_URL')."/profile/$modelId";
                case 'company':     return env('APP_URL')."/company/$modelId";
            }
        }
    }
}