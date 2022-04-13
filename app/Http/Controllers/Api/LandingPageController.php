<?php

namespace App\Http\Controllers\Api;


use Illuminate\Support\Facades\DB;
use App\Traits\HashtagFactory;
use Illuminate\Support\Collection;
use App\Channel\Payload;
use App\Collaborate;
use App\Collaborate\Applicant;
use App\CompanyUser;
use App\Console\Commands\Build\Cache\Survey;
use Illuminate\Support\Facades\Redis;
use App\Strategies\Paginator;
use Illuminate\Http\Request;
use App\FeedCard;
use App\Payment\PaymentDetails as PaymentPaymentDetails;
use App\Polling;
use App\Product;
use App\PublicReviewProduct;
use App\PublicReviewProduct\Review;
use App\Surveys;
use Carbon\Carbon;
use App\V2\Photo;
use PaymentDetails;

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
                    // echo "m here";
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

    public function carousel($profileId, $model, $companyIds = null)
    {

        $carousel["ui_type"] = "carousel";
        $carousel["model_name"] = $model;
        $carousel["title"] = $model;
        $carousel["see_more"] = true;
        $carousel["elements"] = [];
        
        if ($model == 'collaborate' || $model == 'product_review') {
            $ids = Applicant::where('profile_id','=',$profileId)
                ->pluck('collaborate_id')->toArray();

            // $ids = DB::table("collaborate_applicants")->where("profile_id", $profileId)->pluck("collaborate_id")->toArray();
            
            $collaborateType = $model == 'collaborate' ? $model : 'product-review';
            $carouseldata = Collaborate::where('state','=',1)
                ->whereNotIn('id', $ids)
                ->where('collaborate_type','=',$collaborateType)
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->take(5)->pluck('id')->toArray();
        } elseif ($model == 'surveys') {
            $ids = DB::table("survey_applicants")->where("profile_id", $profileId)
                ->whereNull("deleted_at")
                ->pluck("survey_id")->toArray();
            
            $carouseldata = Surveys::whereNull('deleted_at')
                ->where('state','=',2)
                ->where('is_active','=',1)
                ->where('profile_id', '<>', $profileId)
                ->whereNotIn("id", $ids)
                ->orderBy('created_at', 'desc')
                ->take(5)->pluck('id')->toArray();
        } elseif ($model == 'product') {
            $ids = Review::where('current_status','=',2)
            ->where('profile_id','=',$profileId)
            ->distinct('product_id')
            ->pluck('product_id')->toArray();
            
            //pls put check of excluded profile later
            $carouseldata = PaymentPaymentDetails::where('is_active','=',1)
                ->whereNull('deleted_at')
                ->where('model_type','=','Public Review')
                ->whereNotIn("id", $ids)
                ->orderBy('updated_at', 'desc')
                ->take(5)->pluck('model_id')->toArray();
            
            // $ids =  DB::table("public_product_user_review")->where('profile_id', $profileId)->pluck('product_id')->toArray();
            // $carouseldata =  PublicReviewProduct::select('public_review_products.*')
            //     ->join("payment_details", "payment_details.model_id", "public_review_products.id")
            //     ->whereNull('public_review_products.deleted_at')
            //     ->where('public_review_products.is_active', 1)
            //     ->where('payment_details.is_active', 1)
            //     ->where(function ($query) use ($companyIds) {
            //         if (!empty($companyIds)) {
            //             $query->whereNotIn('public_review_products.company_id', $companyIds)
            //                 ->orWhereNull('public_review_products.company_id');
            //         }
            //     })
            //     ->whereNotIn("public_review_products.id", $ids)
            //     ->orderBy('public_review_products.created_at', 'desc')
            //     ->take(5)->get();
        }
        
        $data = [];
        foreach ($carouseldata as $key => $value) {
            if($model == 'surveys'){
                $data['surveys'] = json_decode(Redis::get("surveys:" . $value), true);
                $surveyModel = Surveys::find($value);
                $data['meta'] = $surveyModel->getMetaFor($profileId);
                if(isset($data['polling']['company_id'])){
                    $data['company'] = json_decode(Redis::get("company:small:".$data['surveys']['company_id'].":V2"), true);
                }else{
                    $data['profile'] = json_decode(Redis::get("profile:small:".$data['surveys']['profile_id'].":V2"), true);
                }
                $data['type'] = $model;
                $data['placeholder_images_meta'] = json_decode('{"meta": {"width": 343,"height": 190,"tiny_photo": "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/banner-images/tiny/1649688161520_learn_earn.png"
                    },
                    "original_photo": "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/banner-images1649688161520_learn_earn.png"
                }');
                $carousel['elements'][] = $data;
            }else if($model == 'collaborate' || $model == 'product_review'){
                $data['collaborate'] = json_decode(Redis::get("collaborate:".$value.":V2"), true);
                $collabModel = Collaborate::find($value);
                $data['meta'] = $collabModel->getMetaForV2($profileId);
                if(isset($data['polling']['company_id'])){
                    $data['company'] = json_decode(Redis::get("company:small:".$data['collaborate']['company_id'].":V2"), true);
                }else{
                    $data['profile'] = json_decode(Redis::get("profile:small:".$data['collaborate']['profile_id'].":V2"), true);
                }
                $data['type'] = 'collaborate';
                $data['placeholder_images_meta'] = json_decode('{"meta": {"width": 343,"height": 190,"tiny_photo": "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/banner-images/tiny/1649688161520_learn_earn.png"
                    },
                    "original_photo": "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/banner-images1649688161520_learn_earn.png"
                }'); 
                $carousel['elements'][] = $data;
            }else if($model == 'product'){
                $data['product'] = json_decode(Redis::get("public-review/product:".$value.":V2"), true);
                $productModel = PublicReviewProduct::find($value);
                $data['meta'] = $productModel->getMetaFor($profileId);
                $data['type'] = 'product';
                $data['placeholder_images_meta'] = json_decode('{"meta": {"width": 343,"height": 190,"tiny_photo": "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/banner-images/tiny/1649688161520_learn_earn.png"
                    },
                    "original_photo": "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/banner-images1649688161520_learn_earn.png"
                }'); 
                $carousel['elements'][] = $data;
            }
        }
        return $carousel;
    }
    
    public function poll($profileId, $type, $companyIds = null)
    {
        $carousel["ui_type"] = "carousel";
        $carousel["model_name"] = "polling";
        $carousel["see_more"] = true;
        $carousel["elements"] = [];
        
        if($type == 'TagTaste'){
            $carousel["title"] = "Polls From Tagtaste";
            $carouseldata = Polling::leftJoin('poll_votes', 'poll_votes.poll_id', 'poll_questions.id')
                ->where('poll_votes.profile_id', '<>', $profileId)
                ->whereNull('poll_votes.deleted_at')
                ->where('poll_questions.is_expired', 0)
                ->where('poll_questions.profile_id', '<>', $profileId)  
                ->whereNull('poll_questions.deleted_at')
                ->whereIn('poll_questions.company_id',[config("constant.TAGTASTE_POLL_COMPANY_ID")])
                ->orderBy('poll_questions.created_at', 'desc')
                ->take(10)->pluck('poll_questions.id')->toArray();
        }else{
            $carousel["title"] = "Polls From Community";
            $carouseldata = Polling::leftJoin('poll_votes', 'poll_votes.poll_id', 'poll_questions.id')
                ->where('poll_votes.profile_id', '<>', $profileId)
                ->whereNull('poll_votes.deleted_at')
                ->where('poll_questions.is_expired', 0)
                ->where('poll_questions.profile_id', '<>', $profileId)  
                ->whereNull('poll_questions.deleted_at')
                ->whereNotIn('poll_questions.company_id',[config("constant.TAGTASTE_POLL_COMPANY_ID")])
                ->orWhereNull('poll_questions.company_id')
                ->orderBy('poll_questions.created_at', 'desc')
                ->take(10)->pluck('poll_questions.id')->toArray(); 
        }

        foreach ($carouseldata as $key => $value) {
            $data['polling'] = json_decode(Redis::get("polling:" . $value), true);
            $pollModel = Polling::find($value);
            $data['meta'] = $pollModel->getMetaForV2($profileId);
            if(isset($data['polling']['company_id'])){
                $data['company'] = json_decode(Redis::get("company:small:".$data['polling']['company_id'].":V2"), true);
            }else{
                $data['profile'] = json_decode(Redis::get("profile:small:".$data['polling']['profile_id'].":V2"), true);
            }
            $data['type'] = 'polling';
            $data['placeholder_images_meta'] = json_decode('{"meta": {"width": 343,"height": 190,"tiny_photo": "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/banner-images/tiny/1649688161520_learn_earn.png"
                },
                "original_photo": "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/banner-images1649688161520_learn_earn.png"
            }');
            $carousel['elements'][] = $data;
        }
        return $carousel;
    }

    public function participatedExpiredpoll($profileId)
    {
        $carousel["ui_type"] = "carousel";
        $carousel["model_name"] = "polling";
        $carousel["title"] = "Polls in which you have participated";
        $carousel["see_more"] = true;
        $carousel["value"] = "poll_result";
        $carousel["elements"] = [];

        $carouseldata = Polling::join('poll_votes', 'poll_votes.poll_id', 'poll_questions.id')
            ->where('poll_votes.profile_id', $profileId)
            ->where('poll_questions.is_expired', 1)
            ->whereNull('poll_votes.deleted_at')
            ->whereNull('poll_questions.deleted_at')
            ->where('poll_questions.created_at', '>=', Carbon::now()->subDays(7)->toDateTimeString())
            ->distinct('poll_questions.id')
            ->orderBy('poll_questions.created_at', 'desc')
            ->take(10)->pluck('poll_questions.id')->toArray();


        foreach ($carouseldata as $key => $value) {
            $data['polling'] = json_decode(Redis::get("polling:" . $value), true);
            $pollModel = Polling::find($value);
            $data['meta'] = $pollModel->getMetaForV2($profileId);
            if(isset($data['polling']['company_id'])){
                $data['company'] = json_decode(Redis::get("company:small:".$data['polling']['company_id'].":V2"), true);
            }else{
                $data['profile'] = json_decode(Redis::get("profile:small:".$data['polling']['profile_id'].":V2"), true);
            }
            $data['type'] = 'polling';
            $data['placeholder_images_meta'] = json_decode('{"meta": {"width": 343,"height": 190,"tiny_photo": "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/banner-images/tiny/1649688161520_learn_earn.png"
                },
                "original_photo": "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/banner-images1649688161520_learn_earn.png"
            }');
            $carousel['elements'][] = $data;
        }
        return $carousel;
    }


    public function imageCarousel($profileId)
    {
        $carousel["ui_type"] = "image_carousel";
        $carousel["title"] = "Tagtaste Insights";
        $carousel["model_name"] = "hashtag";
        $carousel["model_id"] = "ttinsights";
        $carousel["see_more"] = true;
        
        $carousel["elements"] = [];

        $payloads = Payload::where('model','App\\V2\\Photo')
            ->whereNull('deleted_at')
            ->where('channel_name','company.public.45')
            ->orderBy('created_at', 'desc')
            ->take(5)->get();

        $carouseldata = $this->getPayloadData($payloads, $profileId);
        $carousel["elements"] = $carouseldata;
        return $carousel;
    }

    public function getSuggestion($profileId)
    {
        $client = config('database.neo4j_uri_client');

        //models - product-review, product, collaborate, surveys, polling 
        $productReviewSuggs = $this->getModelSuggestionIds($client, $profileId, 'product-review');
        $productSuggs = $this->getModelSuggestionIds($client, $profileId, 'product');
        $collaborateSugges = $this->getModelSuggestionIds($client, $profileId, 'collaborate');
        $surveySugges = $this->getModelSuggestionIds($client, $profileId, 'surveys');
        $pollSugges = $this->getModelSuggestionIds($client, $profileId, 'polling');

        $tempMixSuggs = [];

        $suggCount = 0;
        while ($suggCount <= 5) {
            array_push($tempMixSuggs, array_shift($productReviewSuggs));
            array_push($tempMixSuggs, array_shift($productSuggs));
            array_push($tempMixSuggs, array_shift($collaborateSugges));
            array_push($tempMixSuggs, array_shift($surveySugges));
            array_push($tempMixSuggs, array_shift($pollSugges));

            if ((count($productReviewSuggs) + count($productSuggs) + count($collaborateSugges)
                + count($surveySugges) + count($pollSugges)) == 0) {
                break;
            }
            $suggCount = count($tempMixSuggs);
        }
        $suggestionsList = array_slice($tempMixSuggs, 0, 5, true);
        $finalSuggestionData = [];
        foreach ($suggestionsList as $suggObj) {
            $dataObj = $this->getModelSuggestion($client, $profileId, $suggObj);
            if (!is_null($dataObj) && count($dataObj) > 0) {
                $finalSuggestionData[] = $dataObj;
            }
        }

        return $finalSuggestionData;
    }

    protected function getModelSuggestionIds($client, $profileId, $modelName)
    {
        switch ($modelName) {
            case 'product':
                $query = "MATCH (user:User {profile_id:$profileId}) -[:FOLLOWS{following:1}]-> (users:User), (product:Product)
                WHERE NOT ((user) -[:REVIEWED]->(product)) AND ((users) -[:REVIEWED]->(product)) 
                WITH product, rand() AS number
                ORDER BY number
                return product.product_id LIMIT 3;";

                $result = $client->run($query);
                $data = [];
                foreach ($result->records() as $record) {
                    array_push($data, [
                        'id' => $record->get('product.product_id'),
                        'model_name' => 'product'
                    ]);
                }
                return $data;
                break;

            case 'surveys':
                $query = "MATCH (user:User {profile_id:$profileId}) -[:FOLLOWS{following:1}]-> (users:User), (survey:Surveys)
                WHERE NOT ((user) -[:SURVEY_PARTICIPATION]->(survey)) AND ((users) -[:SURVEY_PARTICIPATION]->(survey)) AND survey.profile_id <> $profileId
                WITH survey, rand() AS number
                ORDER BY number
                return survey.survey_id, survey.payload_id LIMIT 3;";

                $result = $client->run($query);
                $data = [];
                foreach ($result->records() as $record) {
                    array_push($data, [
                        'id' => $record->get('survey.survey_id'),
                        'payload_id' => $record->get('survey.payload_id'),
                        'model_name' => 'surveys'
                    ]);
                }
                return $data;
                break;
            case 'polling':
                $query = "MATCH (user:User {profile_id:$profileId}) -[:FOLLOWS{following:1}]-> (users:User), (polls:Polling)
                WHERE NOT ((user) -[:POLL_PARTICIPATION]->(polls)) AND ((users) -[:POLL_PARTICIPATION]->(polls)) AND polls.profile_id <> $profileId
                WITH polls, rand() AS number
                ORDER BY number
                return polls.poll_id, polls.payload_id LIMIT 3;";
                // echo $query;
                $result = $client->run($query);
                $data = [];
                foreach ($result->records() as $record) {
                    array_push($data, [
                        'id' => $record->get('polls.poll_id'),
                        'payload_id' => $record->get('polls.payload_id'),
                        'model_name' => 'polling'
                    ]);
                }
                return $data;
                break;
            case 'collaborate':
                $query = "MATCH (user:User {profile_id:$profileId}) -[:FOLLOWS{following:1}]-> (users:User), (collabs:Collaborate)
                WHERE NOT ((user) -[:SHOWN_INTEREST]->(collabs)) AND ((users) -[:SHOWN_INTEREST]->(collabs)) AND collabs.profile_id <> $profileId AND collabs.collaborate_type = 'collaborate'
                WITH collabs, rand() AS number
                ORDER BY number
                return collabs.collaborate_id,collabs.payload_id LIMIT 3;";

                $result = $client->run($query);
                $data = [];
                foreach ($result->records() as $record) {
                    array_push($data, [
                        'id' => $record->get('collabs.collaborate_id'),
                        'payload_id' => $record->get('collabs.payload_id'),
                        'model_name' => 'collaborate'
                    ]);
                }
                return $data;
                break;
            case 'product-review':
                $query = "MATCH (user:User {profile_id:$profileId}) -[:FOLLOWS{following:1}]-> (users:User), (collabs:Collaborate)
                WHERE NOT ((user) -[:SHOWN_INTEREST]->(collabs)) AND ((users) -[:SHOWN_INTEREST]->(collabs)) AND collabs.profile_id <> $profileId AND collabs.collaborate_type = 'product-review'
                WITH collabs, rand() AS number
                ORDER BY number
                return collabs.collaborate_id,collabs.payload_id LIMIT 3;";

                $result = $client->run($query);
                $data = [];
                foreach ($result->records() as $record) {
                    array_push($data, [
                        'id' => $record->get('collabs.collaborate_id'),
                        'payload_id' => $record->get('collabs.payload_id'),
                        'model_name' => 'product-review'
                    ]);
                }
                return $data;
                break;
            default:
                return null;
                break;
        };
    }

    protected function getModelSuggestion($client, $profileId, $suggestionObj)
    {
        $data = null;
        if ($suggestionObj['model_name'] == 'product') {
            $productId = $suggestionObj['id'];
            $key = 'public-review/product:' . $productId . ':V2';
            $cachedData = Redis::connection('V2')->get($key);
            $product = json_decode($cachedData, true);

            $productModel = \App\PublicReviewProduct::find($productId);

            if ($product != null) {
                $product = [
                    'product' => $product,
                    'meta' => $productModel->getMetaFor($profileId),
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
                $data = [
                    "ui_type" => "suggestion",
                    "title" => "Suggested for you",
                    "total_count" => count($result->records()),
                    "profiles" => $showProfiles,
                    "sub_title" => $subTitle,
                    "suggestion" => $product
                ];
            }
            return $data;
        } else {
            // $modelName = ucfirst($suggestionObj['model_name']);
            $modelName = $suggestionObj['model_name'];

            $payloads = Payload::where('id', '=', $suggestionObj['payload_id'])->whereNull('deleted_at')->get();
            $modelData = $this->getPayloadData($payloads, $profileId);
            if (count($modelData) > 0) {
                $query = '';
                $modelId = $suggestionObj['id'];
                if ($modelName == 'polling') {
                    $query = "MATCH (users:User) -[:POLL_PARTICIPATION]-> (polls:Polling{poll_id:$modelId})
                        WITH users, rand() as number
                        ORDER BY number   
                        RETURN users;";
                } else if ($modelName == 'surveys') {
                    $query = "MATCH (users:User) -[:SURVEY_PARTICIPATION]-> (survey:Surveys{survey_id:'$modelId'})
                    WITH users, rand() as number
                    ORDER BY number   
                    RETURN users;";
                } else if ($modelName == 'collaborate') {
                    $query = "MATCH (users:User) -[:SHOWN_INTEREST]-> (collab:Collaborate{collaborate_id:$modelId})
                        WHERE collab.collaborate_type = 'collaborate'
                        WITH users, rand() as number
                        ORDER BY number   
                        RETURN users;";
                } else if ($modelName == 'product-review') {
                    $query = "MATCH (users:User) -[:SHOWN_INTEREST]-> (collab:Collaborate{collaborate_id:$modelId})
                    WHERE collab.collaborate_type = 'product-review'
                    WITH users, rand() as number
                    ORDER BY number   
                    RETURN users;";
                }
                // echo $query;
                $result = $client->run($query);
                $totalProfileCount = count($result->records());
                $showProfileCount = 3;

                $showProfiles = [];
                $slicedProfileList = array_slice($result->records(), 0, $showProfileCount, true);

                foreach ($slicedProfileList as $profileData) {
                    array_push($showProfiles, $profileData->get('users')->values());
                }

                $subTitle = '';
                if ($modelName == 'polling' || $modelName == 'surveys') {
                    $subTitle = 'others participated';
                    if ($totalProfileCount <= $showProfileCount) {
                        $subTitle = 'participated';
                    } else if ($totalProfileCount <= ($showProfileCount + 1)) {
                        $subTitle = 'other participated';
                    }
                } else {
                    $subTitle = 'others showed interest';
                    if ($totalProfileCount <= $showProfileCount) {
                        $subTitle = 'showed interest';
                    } else if ($totalProfileCount <= ($showProfileCount + 1)) {
                        $subTitle = 'other showed interest';
                    }
                }

                return $data = [
                    "ui_type" => "suggestion",
                    "title" => "Suggested for you",
                    "total_count" => $totalProfileCount,
                    "profiles" => $showProfiles,
                    "sub_title" => $subTitle,
                    "suggestion" => $modelData[0]
                ];
            }
        }
    }

    private function getPayloadData(&$payloads, &$profileId)
    {
        $indexTypeV2 = array("shared", "company", "sharedBy", "shoutout", "profile", "collaborate");
        $index = 0;
        //dd($payloads);
        $finalData = [];
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
                    // echo "payload id".$payload->model_id;
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
            $finalData[] = $data;
        }

        return $finalData;
    }
    
    public function landingPage(Request $request)
    {
        $companyIds = CompanyUser::where("profile_id", $request->user()->profile->id)->get()->pluck("id");
        $this->errors['status'] = 0;
        $profileId = $request->user()->profile->id;

        //improvement needed
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

        //improvement needed
        $current_post_count =  DB::table('landing_banner')->select('images_meta', 'model_name', 'model_id')->where('banner_type', 'big_banner')->whereNull('deleted_at')->where('is_active', 1)->where('created_at', '>=', date('Y-m-d 00:00:00'))->orderByRaw("RAND()")->count();
        $elements =  DB::table('landing_banner')->select('images_meta', 'model_name', 'model_id')->where('banner_type', 'big_banner')->whereNull('deleted_at')->where('is_active', 1)->where('created_at', '>=', date('Y-m-d 00:00:00'))->orderByRaw("RAND()")->limit(15)->get();

        if ($current_post_count < 15) {
            $past_posts_count = 15 - $current_post_count;
            $past_elements =  DB::table('landing_banner')->select('images_meta', 'model_name', 'model_id')->where('banner_type', 'big_banner')->whereNull('deleted_at')->where('is_active', 1)->where('created_at', '<', date('Y-m-d 00:00:00'))->orderBy("updated_at")->take($past_posts_count)->get();
            if (count($past_elements) != 0) $elements = $elements->merge($past_elements);
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

            //Need to make it dynamic
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
        if (count($suggestion) > 0) {
            array_push($this->model, ...$suggestion);
        }
        // $this->model = [];
        // $this->model[] = $suggestion;
        // return $this->sendResponse();


        $carouselCollab = $this->carousel($profileId, 'collaborate', $companyIds);
        if (count($carouselCollab["elements"]) != 0)
            $this->model[] = $carouselCollab;

        $carouselSurvey = $this->carousel($profileId, 'surveys', $companyIds);
        if (count($carouselSurvey["elements"]) != 0)
            $this->model[] = $carouselSurvey;


        $carouselProduct = $this->carousel($profileId, 'product_review', $companyIds);
        if (count($carouselProduct["elements"]) != 0)
            $this->model[] = $carouselProduct;

        $carouselPublicReview = $this->carousel($profileId, 'product', $companyIds);
        if (count($carouselPublicReview["elements"]) != 0)
            $this->model[] = $carouselPublicReview;


        $poll = $this->poll($profileId, 'TagTaste');
        if (count($poll["elements"]) != 0)
            $this->model[] = $poll;


        $pollNotTagtaste = $this->poll($profileId, 'NotTagTaste');
        if (count($pollNotTagtaste["elements"]) != 0)
            $this->model[] = $pollNotTagtaste;

        
        $expiredPoll = $this->participatedExpiredpoll($profileId);
        if (count($expiredPoll["elements"]) != 0)
            $this->model[] = $expiredPoll;        


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
        $feed["title"] = "From Your Feed";
        $feed["see_more"] = true;
        $feed["total_count"] = 5;

        $this->model[] = $feed;
        return $this->sendResponse();
    }
}
