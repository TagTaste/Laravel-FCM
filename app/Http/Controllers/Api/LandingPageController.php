<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\DB;
use App\Traits\HashtagFactory;
use Illuminate\Support\Collection;
use App\Channel\Payload;
use Illuminate\Support\Facades\Redis;
use App\Strategies\Paginator;
use Illuminate\Http\Request;



class LandingPageController extends Controller
{
    use HashtagFactory;

    protected $model = [];
    protected $feed_card = [];
    protected $feed_card_count = 0;
    protected $modelNotIncluded = [];

    /**
     * Display a listing of the quick links.
     *
     * @return Response
     */
    
    public function quickLinks(Request $request)
    {

        $this->errors['status'] = 0;

        $quick_links =   DB::table('landing_quick_links')->select('id', 'title', 'image', 'model_name')->whereNull('deleted_at')->where('is_active', 1)->get();
        $data["ui_type"] = "quick_links";
        $data["elements"] = $quick_links;
        $this->model[] = $data;
        return $this->sendResponse();
    }

    /**
     * Display a listing of right sidebar data.
     *
     * @return Response
     */
    public function sideData(Request $request)
    {

        $this->errors['status'] = 0;

        //passbook
        $passbook["ui_type"] = "passbook";

        //products available
        $products["ui_type"] = "product_available";
        $products["title"] = "3 Products available";
        $products["sub_title"] = "Review Now";
        $products["images_meta"] = "";

        $this->model[] = $passbook;
        $this->model[] = $products;

        //banner
        $banner =   DB::table('landing_banner')->select('images_meta', 'model_name', 'model_id')->where('banner_type', 'banner')->whereNull('deleted_at')->where('is_active', 1)->first();
        if ($banner) {
            $banner->ui_type = "banner";
            $this->model[] = $banner;
        }

        $tags = [];
        $tags = $this->trendingHashtags();
        foreach ($tags as &$tag) {

            unset($tag["updated_at"]);
        }

        //hashtags
        $hashtags["ui_type"] = "hashtags";
        $hashtags["title"] = "Trending #tags";
        $hashtags["see_more"] = true;
        $hashtags["elements"] = $tags;
        $this->model[] = $hashtags;

        return $this->sendResponse();
    }

    public function feed(Request $request)
    {
        $limit = 0;
        $limit = $request->input('limit');
        if(!$limit)
        {
            $limit =20;
        }
        dd($request->user());
        $profileId = $request->user()->profile->id;
        $payloads = Payload::join('subscribers','subscribers.channel_name','=','channel_payloads.channel_name')
            ->where('subscribers.profile_id',$profileId)
            //Query Builder's where clause doesn't work here for some reason.
            //Don't remove this where query.
            //Ofcourse, unless you know what you are doing.
//            ->whereRaw(\DB::raw('channel_payloads.created_at >= subscribers.created_at'))
            ->orderBy('channel_payloads.created_at','desc')
            ->skip(0)
            ->take($limit)
            ->get();
        if($payloads->count() === 0){
            $this->errors[] = 'No more feed';
            return $this->sendResponse();
        }
        $this->getMeta($payloads,$profileId);
        return $this->sendResponse();
    }

    public function getSurveyApplicantCount($modelData)
    {

        return $modelData->totalApplicants;
    }

    private function getType($modelName)
    {
        $exploded = explode('\\',$modelName);
        return strtolower(end($exploded));
    }
    private function getMeta(&$payloads, &$profileId)
    {

        foreach($payloads as $payload){
            $type = null;
            $data = [];

            $cached = json_decode($payload->payload, true);

            foreach($cached as $name => $key){
                $cachedData = Redis::get($key);
                if(!$cachedData){
                    \Log::warning("could not get from $key");
                }
                $data[$name] = json_decode($cachedData,true);
            }
            
            if($payload->model !== null){
                $model = $payload->model;
                $type = $this->getType($payload->model);
                if($model=='App\Surveys'){
                    $model = $model::with([])->where('payload_id',$payload->id)->where('state','=',config("constant.SURVEY_STATES.PUBLISHED"))->first();
                }else{
                    $model = $model::with([])->where('id',$payload->model_id)->first();
                }

                if ($model != null && $type == "surveys") {
                    $data["surveys"]["totalApplicants"] = $this->getSurveyApplicantCount($model);
                }
                
                if($model !== null && method_exists($model, 'getMetaFor') && $profileId != null){
                    $data['meta'] = $model->getMetaFor($profileId);;
                }
            }
            $data['type'] = $type;  
            $this->model[] = $data;
        }
    }
}
