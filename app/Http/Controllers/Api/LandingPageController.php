<?php

namespace App\Http\Controllers\Api;


use Illuminate\Support\Facades\DB;
use App\Traits\HashtagFactory;
use Illuminate\Support\Collection;
use App\Channel\Payload;
use App\Collaborate;
use Illuminate\Support\Facades\Redis;
use App\Strategies\Paginator;
use Illuminate\Http\Request;
use App\FeedCard;
use App\Polling;
use App\Product;
use App\PublicReviewProduct;
use App\PublicReviewProduct\Review;
use App\Surveys;
use Carbon\Carbon;
use App\V2\Photo;


class LandingPageController extends Controller
{
    use HashtagFactory;

    protected $model = [];
    protected $feed_card = [];
    protected $feed_card_count = 0;
    protected $modelNotIncluded = [];
    protected $placeholderimage = [];

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
            $banner->images_meta = json_decode($banner->images_meta ?? []);
            $this->model[] = $banner;
        }

        $tags = [];
        $tags = $this->trendingHashtags();
        foreach ($tags as &$tag) {

            $tag['total_count'] = $tag['count'];
            unset($tag["count"]);
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

    public function feed(Request $request)
    {
        $this->errors['status'] = 0;
        $limit = 0;
        $limit = $request->input('limit');
        if (!$limit) {
            $limit = 20;
        }
        $profileId = $request->user()->profile->id;

        $this->validatePayloadForVersion($request);
        $this->removeReportedPayloads($profileId);
        $payloads =  Payload::join('subscribers', 'subscribers.channel_name', '=', 'channel_payloads.channel_name')
            ->where('subscribers.profile_id', $profileId)
            ->whereNull('subscribers.deleted_at')
            ->whereNotIn('channel_payloads.id', $this->modelNotIncluded)
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


        $this->model = array_values(array_filter($this->model));
    }

    public function carousel($profileId, $model)
    {
        $carousel["ui_type"] = "carousel";
        $carousel["model_name"] = $model;
        $carousel["title"] = $model;
        $carousel["see_more"] = true;
        $carousel["elements"] = [];

        if ($model == 'collaborate' || $model == 'product_review') {
            $ids = DB::table("collaborate_applicants")->where("profile_id", $profileId)->pluck("collaborate_id")->toArray();
            $carouseldata = Collaborate::select('profiles.*', 'experiences.designation', 'users.name', 'collaborates.id as model_id', 'collaborates.title', 'collaborates.profile_id', 'collaborates.description')
                ->join('profiles', 'collaborates.profile_id', 'profiles.id')
                ->join('users', 'users.id', 'profiles.id')
                ->leftJoin('experiences', 'experiences.profile_id', 'profiles.id')
                ->where('profiles.id', '<>', $profileId)
                ->where('collaborate_type', $model)
                ->whereNull('collaborates.deleted_at')
                ->whereNotIn('collaborates.id', $ids)
                ->orderBy('collaborates.created_at')
                ->where('expires_on', '>=', Carbon::now()->toDateTimeString());
            if ($model == 'product_review') {
                $carouseldata = $carouseldata->where("collaborates.collaborate_type", 'product-review');
            }

            $carouseldata = $carouseldata->take(5)->get();
        } elseif ($model == 'surveys') {
            $ids = DB::table("survey_applicants")->where("profile_id", $profileId)->pluck("survey_id")->toArray();
            $carouseldata = Surveys::select('profiles.*', 'experiences.designation', 'users.name', 'surveys.id as model_id', 'surveys.title', 'surveys.profile_id', 'surveys.description', 'surveys.image_meta as post_meta')
                ->join('profiles', 'surveys.profile_id', 'profiles.id')
                ->join('users', 'users.id', 'profiles.id')
                ->join('experiences', 'experiences.profile_id', 'profiles.id')
                ->where('profiles.id', '<>', $profileId)
                ->whereNull('surveys.deleted_at')
                ->where('surveys.is_active', 1)
                ->whereNotIn("surveys.id", $ids)
                ->orderBy('surveys.created_at', 'desc')
                ->take(5)->get();
        } elseif ($model == 'product') {
            $ids =  DB::table("public_product_user_review")->where('profile_id', $profileId)->pluck('product_id')->toArray();
            $carouseldata =  PublicReviewProduct::select('public_review_products.id as model_id', 'public_review_products.company_name', 'public_review_products.name as title', 'public_review_products.description', 'public_review_products.images_meta as post_meta')
                ->join("payment_details", "payment_details.model_id", "public_review_products.id")
                ->whereNull('public_review_products.deleted_at')
                ->where('public_review_products.is_active', 1)
                ->where('payment_details.is_active', 1)
                ->whereNotIn("public_review_products.id", $ids)
                ->orderBy('public_review_products.created_at', 'desc')
                ->take(5)->get();
        }

        $data = [];
        $profile = [];
        $modelData = [];
        foreach ($carouseldata as $key => $value) {
            $data['meta'] = $value->getMetaFor($profileId);
            $data['placeholder_images_meta'] =  isset($this->placeholderimage[$model]) ? $this->placeholderimage[$model] : json_decode('{
                        "meta": {
                            "width": 343,
                            "height": 190,
                            "tiny_photo": "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/banner-images/tiny/1649688161520_learn_earn.png"
                        },
                        "original_photo": "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/banner-images1649688161520_learn_earn.png"
                    }');
            if ($model != 'product') {
                $profile['id'] = $value->id;
                $profile['tagline'] = $value->tagline;
                $profile['user_id'] = $value->user_id;
                $profile['verified'] = $value->verified;
                $profile['handle'] = $value->handle;
                $profile['image_meta'] = $value->image_meta;
                $profile['is_tasting_expert'] = $value->is_tasting_expert;
                $profile['tasting_instructions'] = $value->tasting_instructions;
                $profile['is_premium'] = $value->is_premium;
                $profile['name'] = $value->name;
                $profile['desigation'] = $value->designation;
                $data['profile'] = $profile;
            } else $modelData['company_name'] = $value->company_name;

            $modelData['id'] = $value->model_id;
            $modelData['title'] = $value->title;
            $modelData['description'] = $value->description;
            $modelData['images_meta'] = isset($value->post_meta) ? $value->post_meta : [];



            $data[$model] = $modelData;
            $carousel['elements'][] = $data;
        }
        return $carousel;
    }

    public function poll($profileId, $type)
    {
        $carousel["ui_type"] = "carousel";
        $carousel["model_name"] = "polling";
        $carousel["title"] = "poll $type";
        $carousel["see_more"] = true;
        $carousel["elements"] = [];


        $carouseldata = Polling::select('profiles.*', 'experiences.designation', 'companies.id', 'poll_questions.id as poll_id', 'poll_questions.title', 'poll_questions.profile_id', 'poll_questions.image_meta as post_meta', 'users.*')
            ->leftJoin('profiles', 'profiles.id', 'poll_questions.profile_id')
            ->leftJoin('users', 'users.id', 'profiles.user_id')
            ->leftJoin('experiences', 'experiences.profile_id', 'profiles.id')
            ->leftJoin('poll_votes', 'poll_votes.poll_id', 'poll_questions.id')
            ->leftJoin('companies', 'companies.id', 'poll_questions.company_id')
            ->whereNull('poll_questions.deleted_at')
            ->where('poll_questions.profile_id', '<>', $profileId)
            ->where('poll_votes.profile_id', '<>', $profileId)
            ->where('poll_questions.is_expired', 0);

        if ($type == 'TagTaste') {
            $carouseldata = $carouseldata->where('companies.id', config("constant.POLL_COMPANY_ID"))
                ->orderBy('poll_questions.created_at', 'desc');
        } elseif ($type == 'NotTagTaste') {
            $carouseldata = $carouseldata->where('companies.id', '<>', config("constant.POLL_COMPANY_ID"))
                ->orderBy('poll_questions.created_at', 'desc');
        }
        $carouseldata = $carouseldata->take(5)->get();

        foreach ($carouseldata as $key => $value) {

            $data["meta"] = $value->getMetaFor($profileId);
            $data['placeholder_images_meta'] =  isset($this->placeholderimage['poll']) ? $this->placeholderimage['poll'] : json_decode('{
                "meta": {
                    "width": 343,
                    "height": 190,
                    "tiny_photo": "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/banner-images/tiny/1649688161520_learn_earn.png"
                },
                "original_photo": "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/banner-images1649688161520_learn_earn.png"
            }');

            $profile['id'] = $value->profile_id;
            $profile['tagline'] = $value->tagline;
            $profile['user_id'] = $value->user_id;
            $profile['verified'] = $value->verified;
            $profile['handle'] = $value->handle;
            $profile['image_meta'] = $value->image_meta;
            $profile['is_tasting_expert'] = $value->is_tasting_expert;
            $profile['tasting_instructions'] = $value->tasting_instructions;
            $profile['is_premium'] = $value->is_premium;
            $profile['name'] = $value->name;
            $profile['designation'] = $value->designation;

            $modelData['id'] = $value->poll_id;
            $modelData['title'] = $value->title;
            $modelData['images_meta'] = isset($value->post_meta) ? $value->post_meta : [];



            $data['profile'] = $profile;
            $data["polling"] = $modelData;
            $carousel['elements'][] = $data;
        }

        return $carousel;
    }

    public function expiredpoll($profileId)
    {
        $carousel["ui_type"] = "carousel";
        $carousel["model_name"] = "polling";
        $carousel["title"] = "poll in which you have participated";
        $carousel["see_more"] = true;
        $carousel["value"] = "result_value";
        $carousel["elements"] = [];

        $carouseldata = Polling::select('profiles.*', 'experiences.designation', 'poll_questions.id as poll_id', 'poll_questions.title', 'poll_questions.profile_id', 'poll_questions.image_meta as post_meta', 'users.name', 'poll_options.text as result')
            ->join('profiles', 'profiles.id', 'poll_questions.profile_id')
            ->join('users', 'users.id', 'profiles.user_id')
            ->join('experiences', 'experiences.profile_id', 'profiles.id')
            ->join('poll_votes', 'poll_votes.poll_id', 'poll_questions.id')
            ->join('poll_options', 'poll_options.poll_id', 'poll_questions.id')
            ->whereNull('poll_questions.deleted_at')
            ->where('poll_questions.profile_id', '<>', $profileId)
            ->where('poll_votes.profile_id', '<>', $profileId)
            ->where('poll_questions.is_expired', 1)
            ->where('poll_questions.expired_time', '>=', Carbon::now()->subDays(7)->toDateTimeString())
            ->orderBy('poll_questions.created_at', 'desc');
        $count["count"] = $carouseldata->count();
        if ($count["count"] <= 2) return $count;
        $carousel["count"] = $count["count"];


        $carouseldata = $carouseldata->take(5)->get();

        foreach ($carouseldata as $key => $value) {
            $data["meta"] = $value->getMetaFor($profileId);
            $data['placeholder_images_meta'] =  isset($this->placeholderimage['poll']) ? $this->placeholderimage['poll'] : json_decode('{
                "meta": {
                    "width": 343,
                    "height": 190,
                    "tiny_photo": "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/banner-images/tiny/1649688161520_learn_earn.png"
                },
                "original_photo": "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/banner-images1649688161520_learn_earn.png"
            }');
            $profile['id'] = $value->profile_id;
            $profile['tagline'] = $value->tagline;
            $profile['user_id'] = $value->user_id;
            $profile['verified'] = $value->verified;
            $profile['handle'] = $value->handle;
            $profile['image_meta'] = $value->image_meta;
            $profile['is_tasting_expert'] = $value->is_tasting_expert;
            $profile['tasting_instructions'] = $value->tasting_instructions;
            $profile['is_premium'] = $value->is_premium;
            $profile['name'] = $value->name;
            $profile['designation'] = $value->designation;

            $modelData['id'] = $value->poll_id;
            $modelData['title'] = $value->title;
            $modelData['images_meta'] = isset($value->post_meta) ? $value->post_meta : [];
            // $modelData['value'] = $result[0];

            $data['profile'] = $profile;
            $data["polling"] = $modelData;
            $carousel['elements'][] = $data;
        }

        return $carousel;
    }


    public function imageCarousel($profileId)
    {
        $carousel["ui_type"] = "image_carousel";
        $carousel["title"] = "Tagtaste Insights";
        $carousel["model_name"] = "hashtag";
        $carousel["model_id"] = "tagtasteInsight";
        $carousel["see_more"] = true;

        $carousel["elements"] = [];
        $photos = Photo::forCompany(config("constant.POLL_COMPANY_ID"))->orderBy('created_at', 'desc')->orderBy('updated_at', 'desc')->take(5)->get();
        $carouseldata = [];

        foreach ($photos as $photo) {
            $photo->images = json_decode($photo->images);
            $photoArray = $photo->toArray();
            $item = $photoArray['owner'];
            unset($item["about"]);
            unset($photoArray["owner"]);
            unset($photoArray["profile_id"]);
            unset($photoArray["company_id"]);

            $carouseldata[] = ['photo' => $photoArray, 'company' => $item, 'meta' => $photo->getMetaFor($profileId), 'type' => 'photo'];
        }
        $carousel["elements"] = $carouseldata;

        return $carousel;
    }

    public function getSuggestion($profileId)
    {
        $client = config('database.neo4j_uri_client');
        //models - collaborate, product-review, product, surveys, polling, 
        $productSuggestionIds = $this->getModelSuggestionIds($client, $profileId, 'product');
        $productSuggestion = $this->getModelSuggestion($client, $profileId, $productSuggestionIds, 'product');
        return $productSuggestion;
    }

    protected function getModelSuggestionIds($client, $profileId, $modelName)
    {
        $query = "MATCH (user:User {profile_id:$profileId}) -[:FOLLOWS]-> (users:User), (product:Product)
        WHERE NOT ((user) -[:REVIEWED]->(product)) AND ((users) -[:REVIEWED]->(product)) 
        WITH product, rand() AS number
        ORDER BY number
        return product.product_id LIMIT 3;";

        $result = $client->run($query);
        $data = [];
        foreach ($result->records() as $record) {
            array_push($data, $record->get('product.product_id'));
        }
        return $data;
    }

    protected function getModelSuggestion($client, $profileId, $suggestionList, $modelName)
    {
        $data = [];
        foreach ($suggestionList as $productId) {
            $product = \App\PublicReviewProduct::where('is_active', 1)
                ->whereNull('deleted_at')
                ->where('id', $productId)->first();

            if ($product != null) {
                $product = [
                    'product' => $product,
                    'meta' => $product->getMetaFor($profileId),
                    'type' => 'product'
                ];

                $query = "MATCH (users:User) -[:REVIEWED]-> (product:Product{product_id:'$productId'})
                WITH users, rand() as number
                ORDER BY number   
                RETURN users;";
                $result = $client->run($query);
                $totalProfileCount = count($result->records());
                $showProfileCount = 3;

                $showProfiles = [];
                $slicedProfileList = array_slice($result->records(), 0, $showProfileCount, true);

                foreach ($slicedProfileList as $profileData) {
                    array_push($showProfiles, $profileData->get('users')->values());
                }
                $subTitle = 'others completed review';
                if ($totalProfileCount <= $showProfileCount) {
                    $subTitle = 'completed review';
                } else if ($totalProfileCount <= ($showProfileCount + 1)) {
                    $subTitle = 'other completed review';
                }
                $suggestionObj = [
                    "ui_type" => "suggestion",
                    "title" => "Suggested for you",
                    "total_count" => count($result->records()),
                    "profiles" => $showProfiles,
                    "sub_title" => $subTitle,
                    "suggestion" => $product
                ];
                array_push($data, $suggestionObj);
            }
        }
        return $data;
    }

    public function landingPage(Request $request)
    {
        $this->errors['status'] = 0;
        $profileId = $request->user()->profile->id;
        $this->validatePayloadForVersion($request);
        $this->removeReportedPayloads($profileId);
        $platform = $request->input('platform');

        if ($platform == 'mobile') {
            $links["ui_type"] = "quick_links";
            $links["elements"] = DB::table('landing_quick_links')->select('id', 'title', 'image', 'model_name')->whereNull('deleted_at')->where('is_active', 1)->get();
            $this->model[] = $links;
        }
        $elements = [];
        $past_elements = [];
        $big_banner["ui_type"] = "big_banner";
        $big_banner["autoplay_duration"] = 3000;
        $big_banner["loop"] = true;
        $big_banner["autoplay"] = true;
        $current_post_count =  DB::table('landing_banner')->select('images_meta', 'model_name', 'model_id')->where('banner_type', 'big_banner')->whereNull('deleted_at')->where('is_active', 1)->where('created_at', '>=', date('Y-m-d 00:00:00'))->orderByRaw("RAND()")->count();
        $elements =  DB::table('landing_banner')->select('images_meta', 'model_name', 'model_id')->where('banner_type', 'big_banner')->whereNull('deleted_at')->where('is_active', 1)->where('created_at', '>=', date('Y-m-d 00:00:00'))->orderByRaw("RAND()")->limit(15)->get();

        if ( $current_post_count< 15) {
            $past_posts_count = 15 - $current_post_count;
            $past_elements =  DB::table('landing_banner')->select('images_meta', 'model_name', 'model_id')->where('banner_type', 'big_banner')->whereNull('deleted_at')->where('is_active', 1)->where('created_at', '<', date('Y-m-d 00:00:00'))->orderBy("updated_at")->take($past_posts_count)->get();
            if(count($past_elements) != 0) $elements =$elements->merge($past_elements);
        }
        foreach ($elements as &$value) {
            $value->images_meta = json_decode($value->images_meta ?? "{}");
            $value->model_id = (string)$value->model_id;
        }
        $big_banner["elements"] = $elements;

        
        $this->model[] = $big_banner;

        if ($platform == 'mobile') {
            $passbook["ui_type"] = "passbook";
            $this->model[] = $passbook;

            $products["ui_type"] = "product_available";
            $products["title"] = "3 Products available";
            $products["sub_title"] = "Review Now";
            $products["image"] = "https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/images/icons/group.png";
            $this->model[] = $products;

            $banner = DB::table('landing_banner')->select('images_meta', 'model_name', 'model_id')->where('banner_type', 'banner')->whereNull('deleted_at')->where('is_active', 1)->orderBy("updated_at", "desc")->first();
            if ($banner) {
                $banner->ui_type = "banner";
                $banner->images_meta = json_decode($banner->images_meta ?? []);
                $this->model[] = $banner;
            }
        }
        $suggestion = $this->getSuggestion($profileId);
        if(count($suggestion) > 0){
            array_push($this->model, ...$suggestion);
        }

        $carouselCollab = $this->carousel($profileId, 'collaborate');
        if (count($carouselCollab["elements"]) != 0)
            $this->model[] = $carouselCollab;

        $carouselSurvey = $this->carousel($profileId, 'surveys');
        if (count($carouselSurvey["elements"]) != 0)
            $this->model[] = $carouselSurvey;

        $carouselProduct = $this->carousel($profileId, 'product_review');
        if (count($carouselProduct["elements"]) != 0)
            $this->model[] = $carouselProduct;

        $carouselPublicReview = $this->carousel($profileId, 'product');
        if (count($carouselPublicReview["elements"]) != 0)
            $this->model[] = $carouselPublicReview;

        $poll = $this->poll($profileId, 'TagTaste');
        if (count($poll["elements"]) != 0)
            $this->model[] = $poll;

        $pollNotTagtaste = $this->poll($profileId, 'NotTagTaste');
        if (count($pollNotTagtaste["elements"]) != 0)
            $this->model[] = $pollNotTagtaste;

        $expiredPoll = $this->expiredpoll($profileId);
        // dd($expiredPoll['count']);
        if ($expiredPoll['count'] > 2) {
            unset($expiredPoll["count"]);
            $this->model[] = $expiredPoll;
        }

        $imageCarousel = $this->imageCarousel($profileId);
        if (count($imageCarousel["elements"]) != 0)
            $this->model[] = $imageCarousel;

        if ($platform == 'mobile') {
            $tags = [];
            $tags = $this->trendingHashtags();
            foreach ($tags as &$tag) {
                $tag["total_count"] = $tag["count"];
                unset($tag["updated_at"]);
                unset($tag["count"]);
            }

            $hashtags["ui_type"] = "hashtag";
            $hashtags["title"] = "Trending #tags";
            $hashtags["see_more"] = true;
            $hashtags["elements"] = $tags;
            $this->model[] = $hashtags;
        }

        $feed["ui_type"] = "feed";
        $feed["total_count"] = 5;
        // $feed["total_count"] = Payload::join('subscribers', 'subscribers.channel_name', '=', 'channel_payloads.channel_name')
        //     ->where('subscribers.profile_id', $profileId)
        //     ->whereNull('subscribers.deleted_at')
        //     ->whereNotIn('channel_payloads.id', $this->modelNotIncluded)
        //     ->orderBy('channel_payloads.created_at', 'desc')
        //     ->count();
        $this->model[] = $feed;
        return $this->sendResponse();
    }
}
