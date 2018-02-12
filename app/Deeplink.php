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

    public function __construct($modelName, $modelId)
    {
        $this->model = $this->getModel($modelName, $modelId);
        if(!$this->model)
            \Log::error("Model: $modelName does not exists");
        $this->modelId = $modelId;
        $this->modelName = $modelName;
    }


    public static function getShortLink($modelName, $modelId)
    {
        $key = 'deeplink:'.$modelName.':'.$modelId;
        if(!\Redis::exists($key)) {
            $self = new self($modelName, $modelId);
            $deeplink = $self->getDeeplinkUrl();
            \Redis::set($key,json_encode($deeplink));
        }
        return (json_decode(\Redis::get($key)))->url;
    }

    public static function getLongLink($modelName, $modelId)
    {
        $key = 'deeplink:'.$modelName.':'.$modelId;
        if(\Redis::exists($key)) {
            return (json_decode(\Redis::get($key)))->url;
        }
        $url = 'https://tagtaste.app.link/?modelName='.$modelName.'&modelID='.$modelId.'&$fallback_url='.urlencode(Deeplink::getActualUrl($modelName, $modelId)).'&$canonical_identifier='.urlencode('share_feed/'.$modelId);
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
                    'shareTypeID' => '-1',
                    'isShared' => 'false',

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

    private static function getActualUrl($modelName, $modelId)
    {
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