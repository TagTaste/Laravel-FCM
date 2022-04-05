<?php

namespace App\Http\Controllers\Api;


use Illuminate\Support\Facades\DB;
use App\Traits\HashtagFactory;
use Illuminate\Support\Collection;
use App\Channel\Payload;
use Illuminate\Support\Facades\Redis;
use App\Strategies\Paginator;
use Illuminate\Http\Request;
use App\FeedCard;



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

    public function quickLinks()
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
    public function sideData()
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

    protected function validatePayloadForVersion($request)
    {
        if (($request->header('x-version') != null
                && $request->header('x-version') < 80) ||
            ($request->header('x-version-ios') != null
                && version_compare("4.2.7", $request->header('x-version-ios'), ">"))
        ) {
            $pollPayloadIds = $this->getNewVersionOfPollPayloads();
            $this->modelNotIncluded = array_merge($this->modelNotIncluded, $pollPayloadIds);
        }
    }

    protected function getNewVersionOfPollPayloads()
    {
        $pollPayloadsWithImage = \App\Polling::where('type', '!=', 3)
            ->pluck('payload_id')->toArray();
        $sharedPollWithImage = \App\Polling::join('polling_shares', 'polling_shares.poll_id', '=', 'poll_questions.id')
            ->where('type', '!=', 3)
            ->pluck('polling_shares.payload_id')
            ->toArray();
        //      return array_merge($pollPayloadsWithImage,$pollPayloadWithOptionImage);
        return  Payload::whereIn('id', array_merge($pollPayloadsWithImage, $sharedPollWithImage))->pluck('channel_payloads.id')->toArray();
    }

    protected function removeReportedPayloads($profileId)
    {
        $reported_payload = Payload::leftJoin('report_content', 'report_content.payload_id', '=', 'channel_payloads.id')
            ->where('report_content.profile_id', $profileId)
            ->pluck('channel_payloads.id')->toArray();
        $this->modelNotIncluded = array_merge($this->modelNotIncluded, $reported_payload);
    }

    public function feed_card_computation($profileId)
    {
        $profile_feed_card = FeedCard::where('data_type', 'profile')->where('is_active', 1)->whereNull('deleted_at')->orderBy('created_at', 'DESC')->first();
        if (!is_null($profile_feed_card)) {
            $this->feed_card['profile_card']['feedCard'] = $profile_feed_card;
            $meta = $profile_feed_card->getMetaFor();
            $meta["isFollowing"] = \App\Profile::isFollowing((int)$profileId, (int)$profile_feed_card["data_id"]);
            $this->feed_card['profile_card']['meta'] = $meta;
            $this->feed_card['profile_card']['type'] = "feedCard";
            $this->feed_card_count = $this->feed_card_count + 1;
        }

        $company_feed_card = FeedCard::where('data_type', 'company')->where('is_active', 1)->whereNull('deleted_at')->orderBy('created_at', 'DESC')->first();
        if (!is_null($company_feed_card)) {
            $this->feed_card['company_card']['feedCard'] = $company_feed_card;
            $meta = $company_feed_card->getMetaFor();
            $meta["isFollowing"] = \App\Company::checkFollowing((int)$profileId, (int)$company_feed_card["data_id"]);
            $this->feed_card['company_card']['meta'] = $meta;
            $this->feed_card['company_card']['type'] = "feedCard";
            $this->feed_card_count = $this->feed_card_count + 1;
        }
    }

    public function feed(Request $request)
    {
        $this->errors['status'] = 0;
        $limit = 0;
        $limit = $request->input('limit');
        if (!$limit) {
            $limit = 20;
        }
        $profileId = $request->user()->profile->id;
        $this->feed_card_computation($profileId);

        $this->validatePayloadForVersion($request);
        $this->removeReportedPayloads($profileId);
        $payloads =  Payload::join('subscribers', 'subscribers.channel_name', '=', 'channel_payloads.channel_name')
            ->where('subscribers.profile_id', $profileId)
            ->whereNull('subscribers.deleted_at')
            ->whereNotIn('channel_payloads.id', $this->modelNotIncluded)
            //Query Builder's where clause doesn't work here for some reason.
            //Don't remove this where query.
            //Ofcourse, unless you know what you are doing.
            //            ->whereRaw(\DB::raw('channel_payloads.created_at >= subscribers.created_at'))
            ->orderBy('channel_payloads.created_at', 'desc')
            ->skip(0)
            ->take($limit)
            ->get();
        if ($payloads->count() === 0) {
            $this->errors[] = 'No more feed';
            return $this->sendResponse();
        }
        $this->getMeta($payloads, $profileId);
        return $this->sendResponse();
    }

    public function getSurveyApplicantCount($modelData)
    {

        return $modelData->totalApplicants;
    }

    private function getType($modelName)
    {
        $exploded = explode('\\', $modelName);
        return strtolower(end($exploded));
    }
    private function getMeta(&$payloads, &$profileId)
    {
        $this->model = array_fill(0, 20, null);



        $indexTypeV2 = array("shared", "company", "sharedBy", "shoutout", "profile", "collaborate");
        $index = 0;
        //dd($payloads);
        foreach ($payloads as $payload) {
            $type = null;
            $data = [];
            $cached = json_decode($payload->payload, true);
            foreach ($cached as $name => $key) {
                $cachedData = null;
                if (in_array($name, $indexTypeV2)) {
                    $key = $key . ":V2";
                    $cachedData = Redis::connection('V2')->get($key);
                } else {

                    $cachedData = Redis::get($key);
                }
                if (!$cachedData) {
                    \Log::warning("could not get from $key");
                }
                $data[$name] = json_decode($cachedData, true);
            }


            if ($payload->model !== null) {
                $model = $payload->model;
                $type = $this->getType($payload->model);
                if ($model == "App\Surveys") {
                    $model = $model::find($data["surveys"]["id"]);
                } else {
                    $model = $model::find($payload->model_id);
                }
                if ($model !== null && method_exists($model, 'getMetaForV2')) {
                    $data['meta'] = $model->getMetaForV2($profileId);
                }
            }
            if ($model != null && $type == "surveys") {
                $data["surveys"]["totalApplicants"] = $this->getSurveyApplicantCount($model);
            }

            $data['type'] = $type;
            $this->model[$index++] = $data;
        }


        if ($this->feed_card_count) {
            if (isset($this->feed_card['profile_card'])) {
                $this->model[$index++] = $this->feed_card['profile_card'];
            }

            if (isset($this->feed_card['company_card'])) {
                $this->model[$index++] = $this->feed_card['company_card'];
            }
        }

        $this->model = array_values(array_filter($this->model));
    }
}
