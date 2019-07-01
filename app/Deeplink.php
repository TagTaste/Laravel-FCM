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
//        if(!\Redis::exists($key)) {
//            $self = new self($modelName, $modelId, $isShared, $share_id);
//            $deeplink = $self->getDeeplinkUrl();
//            \Redis::set($key,json_encode($deeplink));
//        }
//        return (json_decode(\Redis::get($key)))->url;

        $self = new self($modelName, $modelId, $isShared, $share_id);
        return $self->getDeeplinkUrl()->url;
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
                    '$canonical_identifier' =>  $data['deeplinkCanonicalId'],
                    '$og_title' =>              $data['ogTitle'],
                    '$og_description' =>        $data['ogDescription'],
                    '$og_image_url' =>          $data['ogImage'],
//                    '$og_image_width' =>      '273px',
//                    '$og_image_height' =>     '526px',
                    '$og_type' =>               'article',
                    '$og_app_id' =>             env('FACEBOOK_ID'),
                    '$desktop_url' =>           Deeplink::getActualUrl($this->modelName, $this->modelId, $this->shared, $this->share_id),

                    '$twitter_card' =>          $data['cardType'],
                    '$twitter_title' =>         $data['ogTitle'],
                    '$twitter_description' =>   $data['ogDescription'],
                    '$twitter_image_url' =>     $data['ogImage'],
                    '$twitter_site' =>          '@tagtaste',

                    // type and typeID will be deprecated due to branch reserved keyword issue
                    'typeID' =>                 $this->modelId,
                    'type' =>                   $this->modelName,

                    'modelName' =>              $this->modelName,
                    'modelID' =>                $this->modelId,
                    'shareTypeID' =>            $this->share_id,
                    'isShared' =>               $this->shared,

                    'profileID' =>              isset($data['owner']) ? $data['owner'] : null,

                ],
            ],
        ]);
        return json_decode((string)$res->getBody());
    }

    private function getModel(&$modelName, &$id)
    {
        $class = "\\App\\" . ucwords($modelName);
        if($modelName == 'product'||$modelName =='review')
            $class = "\\App\\" . "PublicReviewProduct";
        return $class::find($id);
    }

    public static function getActualUrl($modelName, $modelId, $shared = false, $share_id = 0)
    {
        if($shared) {
            return env('APP_URL')."/feed/view/share/$modelName/$share_id/$modelId";

        } else {
            switch ($modelName) {
                case 'photo':       return env('APP_URL')."/feed/view/photo/$modelId";
                case 'shoutout':    return env('APP_URL')."/feed/view/shoutout/$modelId";
                case 'collaborate': return env('APP_URL')."/feed/view/collaborate/$modelId";
                case 'job':         return env('APP_URL')."/feed/view/jobs/$modelId";
                case 'recipe':      return env('APP_URL')."/recipe/$modelId";
                case 'profile':     return env('APP_URL')."/profile/$modelId";
                case 'company':     return env('APP_URL')."/companies/$modelId";
                case 'product':     return env('APP_URL').'/reviews/products/'.$modelId;
                case 'polling':     return env('APP_URL').'/polling/'.$modelId;
            }
        }
    }
    public static function getDeepLinkText($modelName,$model)
    {
        if( $modelName == 'product' || $modelName == 'profile' || isset($model->owner->name) || $modelName =='company')
        {
            switch ($modelName) {
                case 'photo':       return Deeplink::getPhotoText($model);
                case 'shoutout':    return Deeplink::getShoutoutText($model);
                case 'collaborate': return Deeplink::getCollaborateText($model);
                case 'profile':     return Deeplink::getProfileText($model);
                case 'company':     return Deeplink::getCompanyText($model);
                case 'product':     return Deeplink::getProductText($model);
                case 'polling':     return Deeplink::getPollingText($model);
            }
        }
        else
            return null;
    }

    public static function getShoutoutText($model)
    {
            if($model->preview != null){
                if(!is_null($model->content) && strlen($model->content)) {
                    $description = $model->content;
                } else {
                    $description = isset($model->preview["description"])?$model->preview["description"]:null;
                }
                return $description."\n Checkout this post by ".$model->owner->name." on TagTaste. ";
            }
            if($model->media_url != null && $model->content !=  null){
                return $model->content."\n Checkout this video by ".$model->owner->name." on TagTaste. ";
            }
            return substr($model->content,0,155)."\n Checkout this post by ".$model->owner->name." on TagTaste. ";
    }

    public static function getPhotoText($model)
    {
        $caption = $model->caption;
            return substr($caption,0,155)."\n Checkout this photo by ".$model->owner->name." on TagTaste. ";
    }

    public static function getPollingText($model){

        return "Checkout this poll by ".$model->owner->name." on TagTaste. ";
    }

    public static function getCollaborateText($model)
    {
        return substr($model->description,0,155)." checkout this collaborate by ".$model->owner->name." on TagTaste. ";
    }

    public static function getProductText($model)
    {
        return substr($model->description,0,155)." Checkout ".$model->name." by ".$model->company_name." on TagTaste. ";
    }

    public static function getProfileText($model)
    {
        if(isset($model->about) && !is_null($model->about) && strlen($model->about))
            return substr($model->about,0,155)." checkout this profile on TagTaste. ";
        else
            return "Checkout this profile on TagTaste. ";

    }

    public static function getCompanyText($model)
    {
        if(isset($model->about) && !is_null($model->about) && strlen($model->about))
            return substr($model->about,0,155)." checkout this company on TagTaste. ";
        else
            return "Checkout this company on TagTaste. ";
    }
}