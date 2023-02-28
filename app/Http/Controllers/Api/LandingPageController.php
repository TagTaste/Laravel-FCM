<?php

namespace App\Http\Controllers\Api;


use Illuminate\Support\Facades\DB;
use App\Traits\HashtagFactory;
use Illuminate\Support\Collection;
use App\Channel\Payload;
use App\Collaborate;
use App\Collaborate\Applicant;
use App\Collaborate\BatchAssign;
use App\Collaborate\Review as CollaborateReview;
use App\CompanyUser;
use App\Console\Commands\Build\Cache\Survey;
use Illuminate\Support\Facades\Redis;
use App\Strategies\Paginator;
use Illuminate\Http\Request;
use App\FeedCard;
use App\Payment\PaymentDetails as PaymentPaymentDetails;
use App\Polling;
use App\PollingVote;
use App\Product;
use App\Profile;
use App\PublicReviewProduct;
use App\PublicReviewProduct\Review;
use App\Surveys;
use App\Quiz;
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

    public function quickLinks(Request $request)
    {
        
        $this->errors['status'] = 0;
        $quick_links =   DB::table('landing_quick_links')->select('id', 'title', 'image', 'model_name')->whereNull('deleted_at')->where('is_active', 1);
        if (($request->header('x-version') != null
                && $request->header('x-version') <= 171) ||
            ($request->header('x-version-ios') != null
                && version_compare("5.0.14", $request->header('x-version-ios'), ">="))
        ) {
            $quick_links = $quick_links->where("model_name", "!=", config("constants.LANDING_MODEL.QUIZ"));
        }
        $quick_links = $quick_links->get();

        $data["ui_type"] = config("constant.LANDING_UI_TYPE.QUICK_LINKS");
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
        $profileId = $request->user()->profile->id;
        $this->errors['status'] = 0;

        //passbook
        // $passbook["ui_type"] = config("constant.LANDING_UI_TYPE.PASSBOOK");
        // $this->model[] = $passbook;

        //products available
        $reviewCard = $this->getProductAvailableForReview($profileId);
        if (count($reviewCard) != 0)
            $this->model[] = $reviewCard;

        //banner
        if (!($request->user()->profile->is_paid_taster)) {
            $banner = $this->getBanner();
            if ($banner != null)
                $this->model[] = $banner;
        }

        //hashtags
        $hashTags = $this->getTrendingHashtag();
        if (count($hashTags['elements']) > 0)
            $this->model[] = $hashTags;

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
            ->where('channel_payloads.account_deactivated', 0)
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
                if (isset($data[$name]["image_meta"]) && !is_array($data[$name]["image_meta"])) {
                    $data[$name]["image_meta"] = json_decode($data[$name]["image_meta"], true);
                }
                if (isset($data[$name]["form_json"]) && !is_array($data[$name]["form_json"])) {
                    $data[$name]["form_json"] = json_decode($data[$name]["form_json"], true);
                }
            }


            if ($payload->model !== null) {
                $model = $payload->model;
                $type = $this->getType($payload->model);
                if ($model == "App\Surveys") {
                    $model = $model::find($data["surveys"]["id"]);
                } else if ($model == "App\Quiz") {
                    $model = $model::find($data["quiz"]["id"]);
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
        $carousel["ui_type"] = config("constant.LANDING_UI_TYPE.CAROUSEL");
        $carousel["model_name"] = $model;
        $carousel["title"] = $model;
        $carousel["see_more"] = true;
        $carousel["elements"] = [];

        if ($model == config("constant.LANDING_MODEL.COLLABORATE") || $model == config("constant.LANDING_MODEL.PRODUCT-REVIEW")) {
            $ids = Applicant::where('profile_id', '=', $profileId)
                ->pluck('collaborate_id')->toArray();

            // $ids = DB::table("collaborate_applicants")->where("profile_id", $profileId)->pluck("collaborate_id")->toArray();
            $carouseldata = Collaborate::where('state', '=', 1)
                ->whereNotIn('id', $ids)
                ->where('collaborate_type', '=', $model)
                ->where('account_deactivated', 0)
                ->whereNull('deleted_at')
                ->where('profile_id', '<>', $profileId)
                ->where(function ($query) use ($companyIds) {
                    $query->whereNotIn('company_id', $companyIds)
                        ->orWhereNull('company_id');
                })
                ->orderBy('created_at', 'desc')
                ->take(10)->pluck('id')->toArray();

            $carousel["title"] = "Collaborations";
            if ($model == config("constant.LANDING_MODEL.PRODUCT-REVIEW")) {
                $carousel["title"] = "Product Reviews";
            }
        } else if ($model == config("constant.LANDING_MODEL.SURVEYS")) {
            $ids = DB::table("survey_applicants")->where("profile_id", $profileId)
                ->whereNull("deleted_at")
                ->pluck("survey_id")->toArray();

            $carouseldata = Surveys::whereNull('deleted_at')
                ->where('state', '=', 2)
                ->where('is_active', '=', 1)
                ->where('profile_id', '<>', $profileId)
                ->where('account_deactivated', 0)
                ->whereNotIn("id", $ids)
                ->orderBy('created_at', 'desc')
                ->take(10)->pluck('id')->toArray();

            $carousel["title"] = "Surveys";
        } else if ($model == config("constant.LANDING_MODEL.QUIZ")) {
            $ids = DB::table("quiz_applicants")->where("profile_id", $profileId)
                ->whereNull("deleted_at")
                ->pluck("quiz_id")->toArray();

            $carouseldata = Quiz::whereNull('deleted_at')
                ->where('state', '=', 2)
                ->where('profile_id', '<>', $profileId)
                ->whereNotIn("id", $ids)
                ->orderBy('created_at', 'desc')
                ->take(10)->pluck('id')->toArray();

            $carousel["title"] = "Quizzes";
        } elseif ($model == config("constant.LANDING_MODEL.PRODUCT")) {
            $ids = Review::where('current_status', '=', 2)
                ->where('profile_id', '=', $profileId)
                ->distinct('product_id')
                ->pluck('product_id')->toArray();

            //pls put check of excluded profile later
            $carouseldata = PaymentPaymentDetails::where('is_active', '=', 1)
                ->whereNull('deleted_at')
                ->where('model_type', '=', 'Public Review')
                ->whereNotIn("model_id", $ids)
                ->where('excluded_profiles', 'not like', '%,' . $profileId . ',%')
                ->where('excluded_profiles', 'not like', '%,' . $profileId . '%')
                ->where('excluded_profiles', 'not like', '%' . $profileId . ',%')
                ->orderBy('updated_at', 'desc')
                ->take(40)->pluck('model_id')->toArray();

            $carousel["title"] = "Products you’d like to review";
        }

        foreach ($carouseldata as $key => $value) {
            $data = [];
            $placeHolderImage = json_decode(config("constant.LANDING_PLACEHOLDER_IMAGE")[array_rand(config("constant.LANDING_PLACEHOLDER_IMAGE"))]);
            if ($model == config("constant.LANDING_MODEL.SURVEYS")) {
                $data['surveys'] = json_decode(Redis::get("surveys:" . $value), true);
                $surveyModel = Surveys::find($value);
                $data['meta'] = $surveyModel->getMetaFor($profileId);

                if (isset($data['surveys']['company_id']) && !(is_null($data['surveys']['company_id']))) {
                    $data['company'] = json_decode(Redis::get("company:small:" . $data['surveys']['company_id'] . ":V2"), true);
                } else {
                    $data['profile'] = json_decode(Redis::get("profile:small:" . $data['surveys']['profile_id'] . ":V2"), true);
                }
                $data['type'] = $model;
                $data['placeholder_images_meta'] = $placeHolderImage;
                $carousel['elements'][] = $data;
            }
            if ($model == config("constant.LANDING_MODEL.QUIZ")) {
                $data['quizzes'] = json_decode(Redis::get("quiz:" . $value), true);
                $quizModel = Quiz::find($value);
                $data['meta'] = $quizModel->getMetaFor($profileId);
                if (isset($data['quizzes']['company_id']) && !(is_null($data['quizzes']['company_id']))) {
                    $data['company'] = json_decode(Redis::get("company:small:" . $data['quizzes']['company_id'] . ":V2"), true);
                } else {
                    $data['profile'] = json_decode(Redis::get("profile:small:" . $data['quizzes']['profile_id'] . ":V2"), true);
                }
                $data['type'] = $model;
                $data['placeholder_images_meta'] = $placeHolderImage;
                $carousel['elements'][] = $data;
            } else if ($model == config("constant.LANDING_MODEL.COLLABORATE") || $model == config("constant.LANDING_MODEL.PRODUCT-REVIEW")) {
                $data['collaborate'] = json_decode(Redis::get("collaborate:" . $value . ":V2"), true);
                $collabModel = Collaborate::find($value);
                $data['meta'] = $collabModel->getMetaForV2($profileId);
                if (isset($data['collaborate']['company_id']) && !(is_null($data['collaborate']['company_id']))) {
                    $data['company'] = json_decode(Redis::get("company:small:" . $data['collaborate']['company_id'] . ":V2"), true);
                } else {
                    $data['profile'] = json_decode(Redis::get("profile:small:" . $data['collaborate']['profile_id'] . ":V2"), true);
                }
                $data['type'] = $model;
                $data['placeholder_images_meta'] = $placeHolderImage;
                $carousel['elements'][] = $data;
            } else if ($model == config("constant.LANDING_MODEL.PRODUCT")) {
                $data['product'] = json_decode(Redis::get("public-review/product:" . $value . ":V2"), true);
                $productModel = PublicReviewProduct::find($value);
                $data['meta'] = $productModel->getMetaFor($profileId);
                $data['type'] = $model;
                $data['placeholder_images_meta'] = $placeHolderImage;
                if ($data['meta']['isPaid']) {
                    $carousel['elements'][] = $data;
                }
                if (count($carousel['elements']) >= 10) {
                    break;
                }
            }
        }
        return $carousel;
    }

    public function poll($profileId, $type, $companyIds = null)
    {
        $carousel["ui_type"] = config("constant.LANDING_UI_TYPE.CAROUSEL");
        $carousel["model_name"] = config("constant.LANDING_MODEL.POLLING");
        $carousel["see_more"] = true;
        $carousel["elements"] = [];

        if ($type == 'TagTaste') {
            $carousel["title"] = "TagTaste Polls";
            $ids = PollingVote::where('profile_id', $profileId)
                ->whereNull('deleted_at')
                ->pluck('poll_id')->toArray();

            $carouseldata = Polling::where('is_expired', 0)
                ->where('profile_id', '<>', $profileId)
                ->whereNotIn('id', $ids)
                ->whereNull('deleted_at')
                ->where('account_deactivated', 0)
                ->whereIn('company_id', [config("constant.TAGTASTE_POLL_COMPANY_ID")])
                ->orderBy('created_at', 'desc')
                ->take(10)->pluck('id')->toArray();
        } else {
            $carousel["title"] = "Community Polls";
            $ids = PollingVote::where('profile_id', $profileId)
                ->whereNull('deleted_at')
                ->pluck('poll_id')->toArray();

            $carouseldata = Polling::where('is_expired', 0)
                ->where('profile_id', '<>', $profileId)
                ->where('account_deactivated', 0)
                ->whereNotIn('id', $ids)
                ->whereNull('deleted_at')
                ->where(function ($query) {
                    $query->whereNotIn('company_id', [config("constant.TAGTASTE_POLL_COMPANY_ID")])
                        ->orWhereNull('company_id');
                })
                ->orderBy('created_at', 'desc')
                ->take(10)->pluck('id')->toArray();
        }

        foreach ($carouseldata as $key => $value) {
            $data = [];
            $data['polling'] = json_decode(Redis::get("polling:" . $value), true);
            $pollModel = Polling::find($value);
            $data['meta'] = $pollModel->getMetaForV2($profileId);
            if (isset($data['polling']['company_id']) &&  !(is_null($data['polling']['company_id']))) {
                $data['company'] = json_decode(Redis::get("company:small:" . $data['polling']['company_id'] . ":V2"), true);
            } else {
                $data['profile'] = json_decode(Redis::get("profile:small:" . $data['polling']['profile_id'] . ":V2"), true);
            }
            $data['type'] = config("constant.LANDING_MODEL.POLLING");

            $data['placeholder_images_meta'] = json_decode(config("constant.POLL_PLACEHOLDER_IMAGE")[array_rand(config("constant.POLL_PLACEHOLDER_IMAGE"))]);
            $carousel['elements'][] = $data;
        }
        return $carousel;
    }

    public function participatedExpiredpoll($profileId)
    {
        $carousel["ui_type"] = config("constant.LANDING_UI_TYPE.CAROUSEL");
        $carousel["model_name"] = config("constant.LANDING_MODEL.POLLING");
        $carousel["title"] = "Poll results out";
        $carousel["see_more"] = true;
        $carousel["value"] = "poll_result";
        $carousel["elements"] = [];

        $carouseldata = Polling::join('poll_votes', 'poll_votes.poll_id', 'poll_questions.id')
            ->where('poll_votes.profile_id', $profileId)
            ->where('poll_questions.is_expired', 1)
            ->where('poll_questions.account_deactivated', 0)
            ->whereNull('poll_votes.deleted_at')
            ->whereNull('poll_questions.deleted_at')
            ->where('poll_questions.expired_time', '>=', Carbon::now()->subDays(7)->toDateTimeString())
            ->distinct('poll_questions.id')
            ->orderBy('poll_questions.created_at', 'desc')
            ->take(10)->pluck('poll_questions.id')->toArray();

        if (count($carouseldata) <= 2) {
            $carousel["ui_type"] = config("constant.LANDING_UI_TYPE.SUGGESTION");
        }
        $suggestionList = [];
        foreach ($carouseldata as $key => $value) {
            $data = [];
            $data['polling'] = json_decode(Redis::get("polling:" . $value), true);
            $pollModel = Polling::find($value);
            $data['meta'] = $pollModel->getMetaForV2($profileId);
            if (isset($data['polling']['company_id']) && !(is_null($data['polling']['company_id']))) {
                $data['company'] = json_decode(Redis::get("company:small:" . $data['polling']['company_id'] . ":V2"), true);
            } else {
                $data['profile'] = json_decode(Redis::get("profile:small:" . $data['polling']['profile_id'] . ":V2"), true);
            }
            $data['type'] = config("constant.LANDING_MODEL.POLLING");
            $data['placeholder_images_meta'] = json_decode(config("constant.POLL_PLACEHOLDER_IMAGE")[array_rand(config("constant.POLL_PLACEHOLDER_IMAGE"))]);

            if ($carousel["ui_type"] == config("constant.LANDING_UI_TYPE.SUGGESTION")) {
                $suggestionList[] = [
                    "ui_type" => config("constant.LANDING_UI_TYPE.SUGGESTION"),
                    "title" => "Poll result out",
                    "suggestion" => $data
                ];
            } else {
                $carousel['elements'][] = $data;
            }
        }

        if ($carousel["ui_type"] == config("constant.LANDING_UI_TYPE.SUGGESTION")) {
            return $suggestionList;
        } else {
            return $carousel;
        }
    }


    public function imageCarousel($profileId)
    {
        $carousel["ui_type"] = config("constant.LANDING_UI_TYPE.IMAGE_CAROUSEL");
        $carousel["title"] = "Tagtaste Insights";
        $carousel["model_name"] = config("constant.LANDING_MODEL.HASHTAG");
        $carousel["model_id"] = "#ttinsights";
        $carousel["see_more"] = true;

        $carousel["elements"] = [];
        $companyPayloadName = 'company.public.' . config("constant.TAGTASTE_INSIGHT_COMPANY_ID");
        $payloads = Payload::where('model', 'App\\V2\\Photo')
            ->whereNull('deleted_at')
            ->where('channel_name', $companyPayloadName)
            ->orderBy('created_at', 'desc')
            ->take(10)->get();

        $carouseldata = $this->getPayloadData($payloads, $profileId);
        $carousel["elements"] = $carouseldata;
        return $carousel;
    }

    public function getSuggestion($profileId)
    {
        $client = config('database.neo4j_uri_client');

        //models - product-review, product, collaborate, surveys, polling 
        $productReviewSuggs = $this->getModelSuggestionIds($client, $profileId, config("constant.LANDING_MODEL.PRODUCT-REVIEW"));
        $productSuggs = $this->getModelSuggestionIds($client, $profileId, config("constant.LANDING_MODEL.PRODUCT"));
        $collaborateSugges = $this->getModelSuggestionIds($client, $profileId, config("constant.LANDING_MODEL.COLLABORATE"));
        $surveySugges = $this->getModelSuggestionIds($client, $profileId, config("constant.LANDING_MODEL.SURVEYS"));
        $pollSugges = $this->getModelSuggestionIds($client, $profileId, config("constant.LANDING_MODEL.POLLING"));

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
            // if ((count($productReviewSuggs) + count($collaborateSugges)
            //     + count($surveySugges) + count($pollSugges)) == 0) {
            //     break;
            // }
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
            case config("constant.LANDING_MODEL.PRODUCT"):
                $query = "MATCH (user:User{profile_id:$profileId})-[:FOLLOWS{following:1}]->(users:User)-[:REVIEWED]->(product:Product)
                WHERE NOT (user)-[:REVIEWED]->(product)
                WITH product, rand() AS number
                ORDER BY number
                return product.product_id LIMIT 3;";

                $result = $client->run($query);
                $data = [];
                foreach ($result->records() as $record) {
                    array_push($data, [
                        'id' => $record->get('product.product_id'),
                        'model_name' => $modelName
                    ]);
                }
                return $data;
                break;

            case config("constant.LANDING_MODEL.SURVEYS"):
                $query = "MATCH (user:User{profile_id:$profileId})-[:FOLLOWS{following:1}]-> (users:User)-[:SURVEY_PARTICIPATION]->(survey:Surveys)
                WHERE NOT ((user) -[:SURVEY_PARTICIPATION]->(survey)) AND survey.profile_id <> $profileId
                WITH survey, rand() AS number
                ORDER BY number
                return survey.survey_id, survey.payload_id LIMIT 3;";

                $result = $client->run($query);
                $data = [];
                foreach ($result->records() as $record) {
                    array_push($data, [
                        'id' => $record->get('survey.survey_id'),
                        'payload_id' => $record->get('survey.payload_id'),
                        'model_name' => $modelName
                    ]);
                }
                return $data;
                break;
            case config("constant.LANDING_MODEL.POLLING"):
                $query = "MATCH (user:User {profile_id:$profileId}) -[:FOLLOWS{following:1}]-> (users:User)-[:POLL_PARTICIPATION]->(polls:Polling)
                WHERE NOT ((user) -[:POLL_PARTICIPATION]->(polls)) AND polls.profile_id <> $profileId
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
                        'model_name' => $modelName
                    ]);
                }
                return $data;
                break;
            case config("constant.LANDING_MODEL.COLLABORATE"):
                $query = "MATCH (user:User {profile_id:$profileId}) -[:FOLLOWS{following:1}]-> (users:User)-[:SHOWN_INTEREST]->(collabs:Collaborate)
                WHERE NOT ((user) -[:SHOWN_INTEREST]->(collabs)) AND collabs.profile_id <> $profileId AND collabs.collaborate_type = 'collaborate'
                WITH collabs, rand() AS number
                ORDER BY number
                return collabs.collaborate_id,collabs.payload_id LIMIT 3;";

                $result = $client->run($query);
                $data = [];
                foreach ($result->records() as $record) {
                    array_push($data, [
                        'id' => $record->get('collabs.collaborate_id'),
                        'payload_id' => $record->get('collabs.payload_id'),
                        'model_name' => $modelName
                    ]);
                }
                return $data;
                break;
            case config("constant.LANDING_MODEL.PRODUCT-REVIEW"):
                $query = "MATCH (user:User {profile_id:$profileId}) -[:FOLLOWS{following:1}]-> (users:User)-[:SHOWN_INTEREST]->(collabs:Collaborate)
                WHERE NOT ((user) -[:SHOWN_INTEREST]->(collabs)) AND collabs.profile_id <> $profileId AND collabs.collaborate_type = 'product-review'
                WITH collabs, rand() AS number
                ORDER BY number
                return collabs.collaborate_id,collabs.payload_id LIMIT 3;";

                $result = $client->run($query);
                $data = [];
                foreach ($result->records() as $record) {
                    array_push($data, [
                        'id' => $record->get('collabs.collaborate_id'),
                        'payload_id' => $record->get('collabs.payload_id'),
                        'model_name' => $modelName
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
        if ($suggestionObj['model_name'] == config("constant.LANDING_MODEL.PRODUCT")) {
            $productId = $suggestionObj['id'];
            $key = 'public-review/product:' . $productId . ':V2';
            $cachedData = Redis::connection('V2')->get($key);
            $product = json_decode($cachedData, true);

            $productModel = \App\PublicReviewProduct::find($productId);

            if ($product != null) {
                $product = [
                    'product' => $product,
                    'meta' => $productModel->getMetaFor($profileId),
                    'type' => $suggestionObj['model_name']
                ];

                $query = "MATCH (users:User) -[:REVIEWED]-> (product:Product{product_id:'$productId'}), 
                (user:User{profile_id:$profileId})
                WHERE (user)-[:FOLLOWS{following:1}]->(users)
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
                    "ui_type" => config("constant.LANDING_UI_TYPE.SUGGESTION"),
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
                if ($modelName == config("constant.LANDING_MODEL.POLLING")) {
                    $query = "MATCH (users:User) -[:POLL_PARTICIPATION]-> (polls:Polling{poll_id:$modelId}),
                        (user:User{profile_id:$profileId})
                        WHERE ((user) -[:FOLLOWS{following:1}]-> (users))
                        WITH users, rand() as number
                        ORDER BY number   
                        RETURN users;";
                } else if ($modelName == config("constant.LANDING_MODEL.SURVEYS")) {
                    $query = "MATCH (users:User) -[:SURVEY_PARTICIPATION]-> (survey:Surveys{survey_id:'$modelId'}),
                        (user:User{profile_id:$profileId})
                        WHERE ((user) -[:FOLLOWS{following:1}]-> (users))
                        WITH users, rand() as number
                        ORDER BY number   
                        RETURN users;";
                } else if ($modelName == config("constant.LANDING_MODEL.COLLABORATE")) {
                    $query = "MATCH (users:User) -[:SHOWN_INTEREST]-> (collab:Collaborate{collaborate_id:$modelId}),
                        (user:User{profile_id:$profileId})
                        WHERE collab.collaborate_type = 'collaborate' AND ((user) -[:FOLLOWS{following:1}]-> (users))
                        WITH users, rand() as number
                        ORDER BY number   
                        RETURN users;";
                } else if ($modelName == config("constant.LANDING_MODEL.PRODUCT-REVIEW")) {
                    $query = "MATCH (users:User) -[:SHOWN_INTEREST]-> (collab:Collaborate{collaborate_id:$modelId}),
                        (user:User{profile_id:$profileId})
                        WHERE collab.collaborate_type = 'product-review' AND ((user) -[:FOLLOWS{following:1}]-> (users))
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
                if ($modelName == config("constant.LANDING_MODEL.POLLING") || $modelName == config("constant.LANDING_MODEL.SURVEYS")) {
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
                    "ui_type" => config("constant.LANDING_UI_TYPE.SUGGESTION"),
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
        $collaborateModel = config("constant.LANDING_MODEL.COLLABORATE");
        $surveyModel = config("constant.LANDING_MODEL.SURVEYS");
        $productReviewModel = config("constant.LANDING_MODEL.PRODUCT-REVIEW");
        $pollingModel = config("constant.LANDING_MODEL.POLLING");
        $productModel = config("constant.LANDING_MODEL.PRODUCT");
        $quizModel = config("constant.LANDING_MODEL.QUIZ");
        $companyIds = CompanyUser::where("profile_id", $request->user()->profile->id)->get()->pluck("company_id");
        $this->errors['status'] = 0;
        $profileId = $request->user()->profile->id;

        //improvement needed
        $this->validatePayloadForVersion($request);
        $this->removeReportedPayloads($profileId);
        $platform = $request->input('platform');

        if ($platform == 'mobile') {
            $links["ui_type"] = config("constant.LANDING_UI_TYPE.QUICK_LINKS");

            $quick_links =   DB::table('landing_quick_links')->select('id', 'title', 'image', 'model_name')->whereNull('deleted_at')->where('is_active', 1);
            
            if (($request->header('x-version') != null && $request->header('x-version') <= 171) ||
                ($request->header('x-version-ios') != null && version_compare("5.0.14", $request->header('x-version-ios'), ">="))
            ) {
                $quick_links = $quick_links->where("model_name", "!=", config("constants.LANDING_MODEL.QUIZ"));
            }
            $quick_links = $quick_links->get();    
            $links["elements"] = $quick_links;

            $this->model[] = $links;
        }

        $bigBanner = $this->getBigBanner();
        if (count($bigBanner["elements"]) != 0)
            $this->model[] = $bigBanner;

        if ($platform == 'mobile') {
            $passbook["ui_type"] = config("constant.LANDING_UI_TYPE.PASSBOOK");
            $this->model[] = $passbook;

            $reviewCard = $this->getProductAvailableForReview($profileId);
            if (count($reviewCard) != 0)
                $this->model[] = $reviewCard;

            //banner
            if (!($request->user()->profile->is_paid_taster)) {
                $banner = $this->getBanner();
                if ($banner != null)
                    $this->model[] = $banner;
            }
        }

        $suggestion = $this->getSuggestion($profileId);
        if (count($suggestion) > 0) {
            array_push($this->model, ...$suggestion);
        }

        //Fill in the suggestion for expired poll if expired polls are less than 3
        $expiredPoll = $this->participatedExpiredpoll($profileId);
        $expiredPollUIType = $expiredPoll['ui_type'] ?? '';
        if ($expiredPollUIType != config("constant.LANDING_UI_TYPE.CAROUSEL") && count($expiredPoll) > 0) {
            array_push($this->model, ...$expiredPoll);
        }

        $carouselCollab = $this->carousel($profileId, $collaborateModel, $companyIds);
        if (count($carouselCollab["elements"]) != 0)
            $this->model[] = $carouselCollab;


        $carouselSurvey = $this->carousel($profileId, $surveyModel, $companyIds);
        if (count($carouselSurvey["elements"]) != 0)
            $this->model[] = $carouselSurvey;

        // $carouselQuiz = $this->carousel($profileId, $quizModel, $companyIds);
        // if (count($carouselQuiz["elements"]) != 0)
        //     $this->model[] = $carouselQuiz;

        $carouselProduct = $this->carousel($profileId, $productReviewModel, $companyIds);
        if (count($carouselProduct["elements"]) != 0)
            $this->model[] = $carouselProduct;

        $carouselPublicReview = $this->carousel($profileId, $productModel, $companyIds);
        if (count($carouselPublicReview["elements"]) != 0)
            $this->model[] = $carouselPublicReview;


        $poll = $this->poll($profileId, 'TagTaste');
        if (count($poll["elements"]) != 0)
            $this->model[] = $poll;


        $pollNotTagtaste = $this->poll($profileId, 'NotTagTaste');
        if (count($pollNotTagtaste["elements"]) != 0)
            $this->model[] = $pollNotTagtaste;

        //Fill expired polls in carousel if the count is greater than 2
        if ($expiredPollUIType == config("constant.LANDING_UI_TYPE.CAROUSEL") && count($expiredPoll["elements"]) != 0) {
            $this->model[] = $expiredPoll;
        }

        $imageCarousel = $this->imageCarousel($profileId);
        if (count($imageCarousel["elements"]) != 0)
            $this->model[] = $imageCarousel;


        if ($platform == 'mobile') {
            //hashtags
            $hashTags = $this->getTrendingHashtag();
            if (count($hashTags['elements']) > 0)
                $this->model[] = $hashTags;
        }

        $feed["ui_type"] = config("constant.LANDING_UI_TYPE.FEED");
        $feed["title"] = "From Your Feed";
        $feed["see_more"] = true;
        $feed["total_count"] = 5;


        $this->model[] = $feed;
        return $this->sendResponse();
    }

    public function testLandingPage(Request $request)
    {
        $collaborateModel = config("constant.LANDING_MODEL.COLLABORATE");
        $surveyModel = config("constant.LANDING_MODEL.SURVEYS");
        $productReviewModel = config("constant.LANDING_MODEL.PRODUCT-REVIEW");
        $pollingModel = config("constant.LANDING_MODEL.POLLING");
        $productModel = config("constant.LANDING_MODEL.PRODUCT");
        $quizModel = config("constant.LANDING_MODEL.QUIZ");

        $companyIds = CompanyUser::where("profile_id", $request->user()->profile->id)->get()->pluck("company_id");
        $this->errors['status'] = 0;
        $profileId = $request->user()->profile->id;

        //improvement needed
        $this->validatePayloadForVersion($request);
        $this->removeReportedPayloads($profileId);
        $platform = $request->input('platform');


        $suggestion = $this->getSuggestion($profileId);
        if (count($suggestion) > 0) {
            array_push($this->model, ...$suggestion);
        }

        return $this->sendResponse();

        $carouselCollab = $this->carousel($profileId, $collaborateModel, $companyIds);
        if (count($carouselCollab["elements"]) != 0)
            $this->model[] = $carouselCollab;


        $carouselSurvey = $this->carousel($profileId, $surveyModel, $companyIds);
        if (count($carouselSurvey["elements"]) != 0)
            $this->model[] = $carouselSurvey;


        // $carouselQuiz = $this->carousel($profileId, $quizModel, $companyIds);
        // if (count($carouselQuiz["elements"]) != 0)
        //     $this->model[] = $carouselQuiz;

        $carouselProduct = $this->carousel($profileId, $productReviewModel, $companyIds);
        if (count($carouselProduct["elements"]) != 0)
            $this->model[] = $carouselProduct;

        $carouselPublicReview = $this->carousel($profileId, $productModel, $companyIds);
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
            //hashtags
            $hashTags = $this->getTrendingHashtag();
            if (count($hashTags['elements']) > 0)
                $this->model[] = $hashTags;
        }

        $feed["ui_type"] = config("constant.LANDING_UI_TYPE.FEED");
        $feed["title"] = "From Your Feed";
        $feed["see_more"] = true;
        $feed["total_count"] = 5;


        $this->model[] = $feed;
        return $this->sendResponse();
    }

    public function getProductAvailableForReview($profileId)
    {
        $reviewData = [];
        $completeBatch = CollaborateReview::where('profile_id', $profileId)
            ->where('current_status', 3)
            ->distinct('batch_id')
            ->pluck('batch_id')->toArray();

        $reviewCount = BatchAssign::join('collaborates', 'collaborate_batches_assign.collaborate_id', '=', 'collaborates.id')
            ->where('collaborate_batches_assign.begin_tasting', 1)
            ->where('collaborates.state', 1)
            ->where('collaborates.account_deactivated', 0)
            ->where('collaborate_batches_assign.profile_id', $profileId)
            ->whereNotIn('collaborate_batches_assign.batch_id', $completeBatch)
            ->distinct('collaborate_batches_assign.batch_id')
            ->pluck('collaborate_batches_assign.batch_id')->count();

        if ($reviewCount > 0) {
            $reviewData["ui_type"] = config("constant.LANDING_UI_TYPE.PRODUCT_AVAILABLE");
            $reviewData["title"] = $reviewCount . " Product Available";
            if ($reviewCount > 1) {
                $reviewData["title"] = $reviewCount . " Products Available";
            }
            $reviewData["sub_title"] = "Review Now";
            $reviewData["image"] = "https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/images/icons/group.png";
        }

        return $reviewData;
    }

    public function getBigBanner()
    {
        $bigBanner["ui_type"] = config("constant.LANDING_UI_TYPE.BIG_BANNNER");
        $bigBanner["autoplay_duration"] = 3000;
        $bigBanner["loop"] = true;
        $bigBanner["autoplay"] = true;

        $bigBannerList =  DB::table('landing_banner')
            ->select('updated_at', 'title', 'images_meta', 'model_name', 'model_id', 'filter_meta')
            ->where('banner_type', 'big_banner')
            ->whereNull('deleted_at')
            ->where('is_active', 1)
            ->orderBy('updated_at', 'desc')
            ->limit(15)->get();

        $todayElement = [];
        $pastElement = [];
        foreach ($bigBannerList as &$value) {
            $value->images_meta = json_decode($value->images_meta ?? "{}");
            if ($value->updated_at >= date('Y-m-d 00:00:00')) {
                $todayElement[] = $value;
            } else {
                $pastElement[] = $value;
            }
        }
        shuffle($todayElement);
        shuffle($pastElement);

        if (count($todayElement) > 0 && count($pastElement) > 0) {
            array_push($todayElement, ...$pastElement); //merge all elements in todayElement
            $bigBanner["elements"] = $todayElement;
        } else if (count($todayElement) > 0) {
            $bigBanner["elements"] = $todayElement;
        } else if (count($pastElement) > 0) {
            $bigBanner["elements"] = $pastElement;
        } else {
            $bigBanner["elements"] = [];
        }

        if (count($bigBanner["elements"]) == 1) {
            $bigBanner["autoplay"] = false;
        }

        return $bigBanner;
    }

    public function getTrendingHashtag()
    {
        $tags = [];
        $tags = $this->trendingHashtags();
        foreach ($tags as &$tag) {
            $tag['total_count'] = $tag['count'];
            unset($tag["count"]);
            unset($tag["updated_at"]);
        }
        $hashTags["model_name"] = config("constant.LANDING_MODEL.HASHTAG");

        $hashTags["ui_type"] = config("constant.LANDING_UI_TYPE.HASHTAG");
        $hashTags["title"] = "Trending #Tags";
        $hashTags["see_more"] = true;

        if (count($tags) > 5) {
            $tags = array_slice($tags, 0, 5, true);
        }

        $hashTags["elements"] = $tags;
        return $hashTags;
    }

    public function getBanner()
    {
        // $banner = DB::table('landing_banner')->select('images_meta', 'model_name', 'model_id')->where('banner_type', 'banner')->whereNull('deleted_at')->where('is_active', 1)->first();
        $banner = [
            "ui_type" => config("constant.LANDING_UI_TYPE.BANNER"),
            "images_meta" => json_decode(config("constant.LEARN_AND_EARN_IMAGE")),
            "model_name" => config("constant.LANDING_MODEL.LEARN_AND_EARN"),
            "title" => "Learn and Earn",
            "model_id" => null
        ];
        return $banner;
    }
}
