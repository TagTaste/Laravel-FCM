<?php

namespace App\Http\Controllers\Api;


use Illuminate\Support\Facades\DB;
use App\Traits\HashtagFactory;
use Illuminate\Support\Collection;
use App\Channel\Payload;
use App\Collaborate;
use App\CompanyUser;
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
            $ids = DB::table("collaborate_applicants")->where("profile_id", $profileId)->pluck("collaborate_id")->toArray();
            $carouseldata = Collaborate::select('profiles.*', 'experiences.designation', 'users.name', 'collaborates.id as model_id', 'collaborates.title', 'collaborates.profile_id', 'collaborates.description', 'collaborates.company_id')
                ->join('profiles', 'collaborates.profile_id', 'profiles.id')
                ->join('users', 'users.id', 'profiles.id')
                ->leftJoin('experiences', 'experiences.profile_id', 'profiles.id')
                ->where('profiles.id', '<>', $profileId)
                ->where(function ($query) use ($companyIds) {
                    if (!empty($companyIds)) {
                        $query->whereNotIn('collaborates.company_id', $companyIds)
                            ->orWhereNull('collaborates.company_id');
                    }
                })
                ->whereNull('collaborates.deleted_at')
                ->whereNotIn('collaborates.id', $ids)
                ->orderBy('collaborates.created_at', 'desc')
                ->where('expires_on', '>=', Carbon::now()->toDateTimeString());
            if ($model == 'product_review') {
                $carouseldata = $carouseldata->where("collaborates.collaborate_type", 'product-review');
            } else {
                $carouseldata = $carouseldata->where("collaborates.collaborate_type", 'collaborate');
            }

            $carouseldata = $carouseldata->take(5)->get();
        } elseif ($model == 'surveys') {
            $ids = DB::table("survey_applicants")->where("profile_id", $profileId)->pluck("survey_id")->toArray();
            $carouseldata = Surveys::select('profiles.*', 'experiences.designation', 'users.name', 'surveys.id as model_id', 'surveys.title', 'surveys.company_id', 'surveys.profile_id', 'surveys.description', 'surveys.image_meta as post_meta')
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
            $carouseldata =  PublicReviewProduct::select('public_review_products.*')
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
        $admins = [];
        foreach ($carouseldata as $key => $value) {
            if (!empty($value->company_id)) {
                $admins = DB::table('company_users')->where('company_id', $value->company_id)->pluck('profile_id')->toArray();
            }
            if (!in_array($profileId, $admins)) {
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

                    $modelData['id'] = $value->model_id;
                    $modelData['title'] = $value->title;
                    $modelData['description'] = $value->description;

                    $modelData['images_meta'] = isset($value->post_meta) ? $value->post_meta : [];
                } else {
                    $modelData = $value;
                }

                $data[$model] = $modelData;
                $carousel['elements'][] = $data;
            }
        }
        return $carousel;
    }

    public function poll($profileId, $type, $companyIds = null)
    {
        $carousel["ui_type"] = "carousel";
        $carousel["model_name"] = "polling";
        $carousel["title"] = "poll $type";
        $carousel["see_more"] = true;
        $carousel["elements"] = [];


        $carouseldata = Polling::select('profiles.*', 'experiences.designation', 'companies.id as company_id', 'poll_questions.id as poll_id', 'poll_questions.title', 'poll_questions.profile_id', 'poll_questions.image_meta as post_meta', 'users.*')
            ->leftJoin('profiles', 'profiles.id', 'poll_questions.profile_id')
            ->leftJoin('users', 'users.id', 'profiles.user_id')
            ->leftJoin('experiences', 'experiences.profile_id', 'profiles.id')
            ->leftJoin('poll_votes', 'poll_votes.poll_id', 'poll_questions.id')
            ->leftJoin('companies', 'companies.id', 'poll_questions.company_id')
            ->whereNull('poll_questions.deleted_at')
            ->where('poll_questions.profile_id', '<>', $profileId)
            ->where('poll_votes.profile_id', '<>', $profileId)
            ->where('poll_questions.is_expired', 0)
            ->orderBy('poll_questions.created_at', 'desc');

        if ($type == 'TagTaste') {
            $carouseldata = $carouseldata->where('companies.id', config("constant.POLL_COMPANY_ID"))
                ->orderBy('poll_questions.created_at', 'desc');
        } elseif ($type == 'NotTagTaste') {
            $carouseldata = $carouseldata->where('companies.id', '<>', config("constant.POLL_COMPANY_ID"))
                ->orderBy('poll_questions.created_at', 'desc');
        }
        $carouseldata = $carouseldata->take(5)->get();

        $admins = [];
        foreach ($carouseldata as $key => $value) {
            if (!empty($value->company_id)) {
                $admins = DB::table('company_users')->where('company_id', $value->company_id)->pluck('profile_id')->toArray();
            }
            if (!in_array($profileId, $admins)) {

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
        }

        return $carousel;
    }

    public function expiredpoll($profileId, $companyIds = null)
    {
        $carousel["ui_type"] = "carousel";
        $carousel["model_name"] = "polling";
        $carousel["title"] = "poll in which you have participated";
        $carousel["see_more"] = true;
        $carousel["value"] = "poll_result";
        $carousel["elements"] = [];

        $carouseldata = Polling::select('profiles.*', 'experiences.designation', 'poll_questions.id as poll_id', 'poll_questions.title', 'poll_questions.profile_id', 'poll_questions.company_id', 'poll_questions.image_meta as post_meta', 'users.name', 'poll_options.text as result')
            ->join('profiles', 'profiles.id', 'poll_questions.profile_id')
            ->join('users', 'users.id', 'profiles.user_id')
            ->join('experiences', 'experiences.profile_id', 'profiles.id')
            ->join('poll_votes', 'poll_votes.poll_id', 'poll_questions.id')
            ->join('poll_options', 'poll_options.poll_id', 'poll_questions.id')
            ->whereNull('poll_questions.deleted_at')
            ->where(function ($query) use ($profileId){
                $query->where('poll_questions.profile_id', $profileId)
                        ->orwhere('poll_votes.profile_id', $profileId);
            })
            ->where(function ($query) use ($companyIds) {
                if (!empty($companyIds)) {
                    $query->whereIn('poll_questions.company_id', $companyIds)
                        ->orWhereNull('poll_questions.company_id');
                }
            })
            ->where('poll_questions.is_expired', 1)
            ->where('poll_questions.created_at', '>=', Carbon::now()->subDays(7)->toDateTimeString())
            ->orderBy('poll_questions.created_at', 'desc');
        $count["count"] = $carouseldata->count();
        if ($count["count"] <= 2) return $count;
        $carousel["count"] = $count["count"];


        $carouseldata = $carouseldata->take(5)->get();

        $admins = [];
        foreach ($carouseldata as $key => $value) {
            if (!empty($value->company_id)) {
                $admins = DB::table('company_users')->where('company_id', $value->company_id)->pluck('profile_id')->toArray();
            }
            if (!in_array($profileId, $admins)) {
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
        $feed["title"] = "From Your Feed";
        $feed["see_more"] = true;
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
