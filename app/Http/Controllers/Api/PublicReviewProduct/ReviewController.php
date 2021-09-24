<?php

namespace App\Http\Controllers\Api\PublicReviewProduct;

use App\Comment;
use App\PublicReviewProduct;
use App\PublicReviewProduct\Review;
use App\PublicReviewProduct\ReviewHeader;
use App\Payment\PaymentDetails;
use App\Payment\PaymentLinks;

use Carbon\Carbon;
use App\Traits\CheckTags;
use App\Events\Actions\Tag;
use App\Events\TransactionInit;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use App\Jobs\AddUserInfoWithReview;
use App\Collaborate\Review as PrivateReviewProductReview;
use App\Profile;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    use CheckTags;
    /**
     * Variable to model
     *
     * @var Review
     */
    protected $model;
    protected $now;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public $page;
    public $skip;
    public $take;
    public function __construct(Review $model, Request $request)
    {
        $this->page = $request->input('page') ? intval($request->input('page')) : 1;
        $this->page = $this->page == 0 ? 1 : $this->page;
        $this->take = 20;
        $this->skip = ($this->page - 1) * $this->take;
        $this->model = $model;
        $this->now = Carbon::now()->toDateTimeString();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $productId)
    {
        $product = PublicReviewProduct::where('id', $productId)->first();
        if ($product == null) {
            return $this->sendError("Product is not available");
        }

        //paginate
        $page = $request->input('page') ? intval($request->input('page')) : 1;
        $page = $page == 0 ? 1 : $page;
        $take = $request->input('limit') ? intval($request->input('limit')) : 5;
        $skip = ($page - 1) * $take;

        // sorting
        $sortBy = $request->has('sort_by') ? $request->input('sort_by') : 'DESC';
        $sortBy = $sortBy == 'DESC' ? 'DESC' : 'ASC';

        // gender
        $gender = $request->input('gender');

        // ageRange
        $ageRange = $request->input('ageRange');

        $replacements = array(
            '&lt;18' => '< 18',
            '18-35' => '18 - 35',
            '35-55' => '35 - 55',
            '55-70' => '55 - 70',
            '&gt;70' => '> 70'
        );
        if (!is_null($ageRange) && is_array($ageRange)) {
            foreach ($ageRange as $key => $value) {
                if (isset($replacements[$value])) {
                    $ageRange[$key] = $replacements[$value];
                }
            }
        }

        if (is_null($gender) && is_null($ageRange)) {
            $header = ReviewHeader::where('global_question_id', $product->global_question_id)->where('header_selection_type', 2)->first();
            $this->model = $this->model->where('product_id', $productId)->where('header_id', $header->id)
                ->where('select_type', 5);

            if ($request->has('rating_low'))
                $this->model = $this->model->orderBy('leaf_id', 'ASC')->skip($skip)->take($take)->get();
            else if ($request->has('rating_high'))
                $this->model = $this->model->orderBy('leaf_id', 'DESC')->skip($skip)->take($take)->get();
            else
                $this->model = $this->model->orderBy('updated_at', $sortBy)->skip($skip)->take($take)->get();

            return $this->sendResponse();
        } else {
            $header = ReviewHeader::where('global_question_id', $product->global_question_id)->where('header_selection_type', 2)->first();
            $this->model = $this->model->where('product_id', $productId)->where('header_id', $header->id)
                ->where('select_type', 5);

            if ($request->has('rating_low'))
                $this->model = $this->model->orderBy('leaf_id', 'ASC')->get();
            else if ($request->has('rating_high'))
                $this->model = $this->model->orderBy('leaf_id', 'DESC')->get();
            else
                $this->model = $this->model->orderBy('updated_at', $sortBy)->get();

            $final_data = [];

            foreach ($this->model as $key => $value) {
                if (!is_null($gender) && in_array($value->profile->gender, $gender) && !is_null($ageRange) && in_array($value->profile->ageRange, $ageRange)) {
                    $final_data[] = $value->toArray();
                } else if (!is_null($gender) && in_array($value->profile->gender, $gender) && is_null($ageRange)) {
                    $final_data[] = $value->toArray();
                } else if (is_null($gender) && !is_null($ageRange) && in_array($value->profile->ageRange, $ageRange)) {
                    $final_data[] = $value->toArray();
                } else if (is_null($gender) && is_null($ageRange)) {
                    $final_data[] = $value->toArray();
                }
            }
            $this->model = array_splice($final_data, $skip, $take);
            return $this->sendResponse();
        }
        return $this->sendResponse();
    }

    /**
     * Display a listing of the resource foodshot.
     *
     * @return \Illuminate\Http\Response
     */
    public function foodShot(Request $request, $productId)
    {

        $this->validateProduct($productId);
        //order by
        $sortBy = $request->has('sort_by') ? $request->input('sort_by') : 'DESC';
        $sortBy = $sortBy == 'DESC' ? 'DESC' : 'ASC';

        $food_shots = \App\PublicReviewProduct\Review::where('public_product_user_review.product_id', $productId)
            ->where('public_product_user_review.product_id', $productId)
            ->join('public_review_products as prod', function ($join) {
                $join->on('public_product_user_review.product_id', '=', 'prod.id');
            })
            ->leftJoin('public_review_question_headers as headers', function ($join) {
                $join->on('headers.global_question_id', '=', 'prod.global_question_id');
                $join->where('headers.header_selection_type', 2);
            })
            ->leftJoin('public_product_user_review as r1', function ($join) use ($productId) {
                $join->on('public_product_user_review.profile_id', '=', 'r1.profile_id');
                $join->on('headers.id', '=', 'r1.header_id');
                $join->where('r1.product_Id', '=', $productId);
                $join->where('r1.select_type', 5);
            })
            ->where('public_product_user_review.select_type', 6)
            ->whereNotNull('public_product_user_review.meta')
            ->orderBy('public_product_user_review.updated_at', $sortBy)
            ->where('public_product_user_review.current_status', 2)
            ->skip($this->skip)
            ->take($this->take)
            ->get();

        $this->model = [];

        if (count($food_shots)) {
            $food_shots = $food_shots->toArray();
            foreach ($food_shots as $key => $food_shot) {
                $this->model[] = array(
                    'id' => $food_shot['id'],
                    'product_id' => $food_shot['product_id'],
                    'meta' => $food_shot['meta'],
                );
            }
        }
        return $this->sendResponse();
    }

    public function validateProduct($id)
    {
        $product = PublicReviewProduct::where('id', $id)->first();
        if ($product == null) {
            return $this->sendError("Product is not available");
        }
    }

    /**
     * Display a listing of the resource foodshot.
     *
     * @return \Illuminate\Http\Response
     */
    public function reviewFilter(Request $request, $productId)
    {
        $filters = [];

        $filters[] = [
            "title" => "Age",
            "key" => "ageRange",
            "values" => [
                [
                    "title" => "Below 18",
                    "value" => "<18",
                ],
                [
                    "title" => "18 - 35",
                    "value" => "18-35",
                ],
                [
                    "title" => "35 - 55",
                    "value" => "35-55",
                ],
                [
                    "title" => "55 - 70",
                    "value" => "55-70",
                ],
                [
                    "title" => "Above 70",
                    "value" => ">70",
                ]
            ],
        ];

        $filters[] = [
            "title" => "Gender",
            "key" => "gender",
            "values" => [
                [
                    "title" => "Male",
                    "value" => "Male",
                ],
                [
                    "title" => "Female",
                    "value" => "Female",
                ],
                [
                    "title" => "Other",
                    "value" => "Other",
                ]
            ]
        ];

        $this->model = $filters;

        return $this->sendResponse();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $productId, $headerId)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($productId, $reviewId)
    {
        $product = PublicReviewProduct::where('id', $productId)->first();
        if ($product == null) {
            return $this->sendError("Product is not available");
        }
        $header = ReviewHeader::where('global_question_id', $product->global_question_id)->where('header_selection_type', 2)->first();
        $this->model = $this->model->where('product_id', $productId)->where('id', $reviewId)->where('header_id', $header->id)
            ->where('select_type', 5)->first();

        return $this->sendResponse();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //    public function productReview(Request $request,$productId,$headerId)
    //    {
    //        $this->now = Carbon::now()->toDateTimeString();
    //        $loggedInProfileId = $request->user()->profile->id;
    //        $data = [];
    //        $answers = $request->input('answer');
    //
    //        $checkProduct = \DB::table('public_review_products')->where('id',$productId)->exists();
    //
    //        if(!$checkProduct)
    //        {
    //            return $this->sendError("Wrong product reviewing");
    //        }
    //        Review::where('profile_id',$loggedInProfileId)->where('product_id',$productId)
    //            ->where('header_id',$headerId)->delete();
    //
    //        if(count($answers))
    //        {
    //            foreach ($answers as $answer)
    //            {
    //                $options = isset($answer['option']) ? $answer['option'] : [];
    //                $questionId = $answer['question_id'];
    //                foreach ($options as $option)
    //                {
    //                    $leafId = isset($option['id']) && $option['id'] != 0 ? $option['id'] : null;
    //                    $valueId = isset($option['value_id']) && $option['value_id'] != 0 ? $option['id'] : null;
    //                    $intensity = isset($option['intensity']) && !is_null($option['intensity']) && !empty($option['intensity']) ? $option['intensity'] : null;
    //                    $data[] = ['key'=>null,'value'=>$option['value'],'leaf_id'=>$leafId,
    //                        'question_id'=>$questionId,'header_id'=>$headerId,
    //                        'profile_id'=>$loggedInProfileId,
    //                        'product_id'=>$productId,'intensity'=>$intensity,'current_status'=>1,'value_id'=>$valueId,
    //                        'created_at'=>$this->now,'updated_at'=>$this->now];
    //                }
    //                if(isset($answer['comment']) && !is_null($answer['comment']) && !empty($answer['comment']))
    //                {
    //                    $data[] = ['key'=>"comment",'value'=>$answer['comment'],'leaf_id'=>0,
    //                        'question_id'=>$questionId,'header_id'=>$headerId,
    //                        'profile_id'=>$loggedInProfileId,
    //                        'product_id'=>$productId,'intensity'=>null,'current_status'=>1,'value_id'=>null,
    //                        'created_at'=>$this->now,'updated_at'=>$this->now];
    //                }
    //            }
    //        }
    //        if(count($data)>0)
    //        {
    //            $this->model = $this->model->create($data);
    //        }
    //        return $this->sendResponse();
    //    }

    public function comments(Request $request, $productId, $reviewId)
    {
        $model = $this->model->where('id', $reviewId)->where('product_id', $productId)->first();
        if ($model == null) {
            $this->model = [];
            return $this->sendError("review is not available");
        }
        $page = $request->input('page') ? intval($request->input('page')) : 1;
        $page = $page == 0 ? 1 : $page;
        $this->model = [];
        $this->model = $model->toArray();
        $this->model['comments'] = $model->comments()->orderBy('created_at', 'desc')->skip(($page - 1) * 10)->take(10)->get();
        $this->model['next_page'] = $page > 1 ? $page - 1 : null;
        $this->model['count'] = $model->comments()->count();
        $this->model['previous_page'] = count($this->model['comments']) >= 10 && $page * 10 < $this->model['count']  ? $page + 1 : null;
        return $this->sendResponse();
    }

    public function commentsPost(Request $request, $productId, $reviewId)
    {
        $review = $this->model->where('id', $reviewId)->where('product_id', $productId)->first();
        if ($review == null) {
            $this->model = [];

            return $this->sendError("review is not available");
        }
        $comment = new Comment();
        $content = htmlentities($request->input("content"), ENT_QUOTES, 'UTF-8', false);
        $comment->content = $content;
        $comment->user_id = $request->user()->id;
        $comment->has_tags = $this->hasTags($content);
        $comment->save();
        $review->comments()->attach($comment->id);
        if ($comment->has_tags) {
            event(new Tag($review, $request->user()->profile, $comment->content, null, null, null, $comment));
        } else {
            event(new \App\Events\Actions\Comment($review, $request->user()->profile, $comment->content, null, null, null, $comment));
        }

        $this->model = $comment;

        return $this->sendResponse();
    }

    public function commentsDelete(Request $request, $productId, $reviewId, $commentId)
    {
        $review = $this->model->where('id', $reviewId)->where('product_id', $productId)->first();
        if ($review == null) {
            $this->model = [];

            return $this->sendError("review is not available");
        }
        $this->now = Carbon::now()->toDateTimeString();
        $this->model = $review->comments()->where('id', $commentId)->update(['deleted_at' => $this->now]);
        return $this->sendResponse();
    }

    public function reviewAnswers(Request $request, $productId, $headerId)
    {
        $this->now = Carbon::now()->toDateTimeString();
        $data = [];
        $answers = $request->input('answer');
        $loggedInProfileId = $request->user()->profile->id;
        $product = PublicReviewProduct::where('id', $productId)->first();
        if ($product === null) {
            $this->model = ["status" => false];
            return $this->sendError("Product not found.");
        }
        $userReview = Review::where('profile_id', $loggedInProfileId)->where('product_id', $productId)->orderBy('id', 'desc')->first();
        if (isset($userReview) && $userReview->current_status == 2) {
            $this->model = ["status" => false];
            return $this->sendError("User already reviewd.");
        }
        $currentStatus = $request->has('current_status') ? $request->input('current_status') : 1;

        $this->model = Review::where('profile_id', $loggedInProfileId)->where('product_id', $productId)
            ->where('header_id', $headerId)->delete();

        if (count($answers)) {
            foreach ($answers as $answer) {
                $options = isset($answer['option']) ? $answer['option'] : [];
                $questionId = $answer['question_id'];
                $selectType = isset($answer['select_type']) && !is_null($answer['select_type']) ? $answer['select_type'] : null;
                if (isset($answer['option'])) {
                    $optionVals = \DB::table('public_review_questions')->where('id', $questionId)->get();
                    $optionVals = json_decode($optionVals[0]->questions);
                    if (isset($optionVals->nested_option_list)) {
                        $optionVals = $optionVals->nested_option_list;
                    } else if (isset($optionVals->option)) {
                        $optionVals = $optionVals->option;
                    } else {
                        $optionVals = null;
                    }
                }
                foreach ($options as $option) {
                    $leafId = isset($option['id']) && $option['id'] != 0 ? $option['id'] : null;
                    if ($leafId == null) {
                        $this->model = ["status" => false];
                        return $this->sendError("Leaf id can not null");
                    }
                    if ($optionVals == "AROMA" || $optionVals == "OFFAROMA" || $optionVals == "AROMAFLAVORTASTE" || $optionVals == "Aromatics" || $optionVals == "OFF_AROMAOFF_FLAVOUROFF_TASTE" || $optionVals == "Trigeminal (MouthFeel)") {
                        $option_type = \DB::table('public_review_nested_options')->where('id', $leafId)->select('option_type')->first()->option_type;
                    } else {
                        $option_type = isset($optionVals[$leafId - 1]->option_type) ? $optionVals[$leafId - 1]->option_type : 0;
                    }
                    $valueId = isset($option['value_id']) && $option['value_id'] != 0 ? $option['id'] : null;
                    $intensity = isset($option['intensity']) && !is_null($option['intensity']) && !empty($option['intensity']) ? $option['intensity'] : null;

                    //Added by nikhil to decode &amp to & or anyother other encoding issue
                    $intensity = html_entity_decode($intensity);

                    $data[] = [
                        'key' => null, 'value' => $option['value'], 'leaf_id' => $leafId,
                        'question_id' => $questionId, 'header_id' => $headerId,
                        'profile_id' => $loggedInProfileId, 'product_id' => $productId, 'intensity' => $intensity,
                        'current_status' => $currentStatus, 'value_id' => $valueId,
                        'created_at' => $this->now, 'updated_at' => $this->now, 'select_type' => $selectType, 'meta' => null, 'option_type' => $option_type
                    ];
                }
                if (isset($answer['meta']) && !is_null($answer['meta']) && !empty($answer['meta'])) {
                    $data[] = [
                        'key' => "authenticity_check", 'value' => "meta", 'leaf_id' => 0,
                        'question_id' => $questionId, 'header_id' => $headerId,
                        'profile_id' => $loggedInProfileId, 'product_id' => $productId, 'intensity' => null,
                        'current_status' => $currentStatus, 'value_id' => null,
                        'created_at' => $this->now, 'updated_at' => $this->now, 'select_type' => 6, 'meta' => $answer['meta'], 'option_type' => 0
                    ];
                }
                if (isset($answer['comment']) && !is_null($answer['comment']) && !empty($answer['comment'])) {

                    $data[] = [
                        'key' => "comment", 'value' => $answer['comment'], 'leaf_id' => 0,
                        'question_id' => $questionId, 'header_id' => $headerId,
                        'profile_id' => $loggedInProfileId, 'product_id' => $productId, 'intensity' => null,
                        'current_status' => $currentStatus, 'value_id' => null,
                        'created_at' => $this->now, 'updated_at' => $this->now, 'select_type' => $selectType, 'meta' => null, 'option_type' => 0
                    ];
                }
            }
        }
        $responseData = [];
        $responseData["status"] = true;
        if (count($data) > 0) {
            $this->model = Review::insert($data);
            $responseData = ["status" => true];
            if ($currentStatus == 2) {
                $mandatoryQuestion = \DB::table('public_review_questions')->where('global_question_id', $product->global_question_id)->where('is_mandatory', 1)->where('is_nested_question', 0)->get();
                $mandatoryQuestionsId = $mandatoryQuestion->pluck('id');
                $mandatoryReviewCount = \DB::table('public_product_user_review')->where('product_id', $productId)->whereIn('question_id', $mandatoryQuestionsId)->where('profile_id', $loggedInProfileId)->distinct('question_id')->count('question_id');
                if ($mandatoryQuestion->count() == $mandatoryReviewCount) {
                    $this->model = true;
                    \DB::table('public_review_user_timings')->where('profile_id', $loggedInProfileId)->where('product_id', $productId)->update(['updated_at' => $this->now]);
                    Review::where('profile_id', $loggedInProfileId)->where('product_id', $productId)
                        ->update(['current_status' => $currentStatus]);
                    dispatch(new AddUserInfoWithReview($productId, $loggedInProfileId));
                } else {
                    $this->model = false;
                    $responseData = ["status" => false];
                }
            }
        }

        //NOTE: Check for all the details according to flow and create txn and push txn to queue for further process.
        if ($currentStatus == 2 && $this->model) {
            $responseData = $this->paidProcessing($productId, $request);
        }

        return $this->sendResponse($responseData);
    }
    public function paidProcessing($productId, Request $request)
    {
        $responseData = $flag = [];
        $requestPaid = $request->is_paid ?? false;
        $paymnetExist = PaymentDetails::where('model_id', $productId)->where('is_active', 1)->first();
        if ($paymnetExist != null || $requestPaid) {
            $responseData["status"] = true;
            $responseData["is_paid"] = true;
            //check for paid user
            // if (empty($request->user()->profile->phone)) {
            //     $responseData["title"] = "Uh Oh!";
            //     $responseData["subTitle"] = "Please Contact Admin.";
            //     $responseData["icon"] = "https://s3.ap-south-1.amazonaws.com/static4.tagtaste.com/test/modela_image.png";
            //     $responseData["helper"] = "Phone number not updated";
            // } else
            if ($request->user()->profile->is_paid_taster) {
                //check for count and amount (payment details)
                $profile = true;
                $flag["status"] = false;
            } else {
                $flag["status"] = false;
                //check for global user rules and update euser
                $getPrivateReview = PrivateReviewProductReview::where("profile_id", $request->user()->profile->id)->groupBy("collaborate_id", "batch_id")->where("current_status", 3)->get();
                $getPublicCount = Review::where("profile_id", $request->user()->profile->id)->groupBy("product_id")->where("current_status", 2)->get();

                $profile = false;

                if ($request->user()->profile->is_sensory_trained && (($getPublicCount->count() + $getPrivateReview->count()) >= config("constant.MINIMUM_PAID_TASTER_REVIEWS"))) {
                    Profile::where("id", $request->user()->profile->id)->update(["is_paid_taster" => 1]);
                    $profile = true;
                }
            }
            $request->merge(["product_id" => $productId]);
            if ($profile && $paymnetExist != null) {
                $flag = $this->verifyPayment($paymnetExist, $request);
            }

            $responseData['is_paid_taster'] = $profile;
            if (!$profile) {
                $responseData["get_paid"] = false;
                // $responseData["title"] = "Uh Oh!";
                // $responseData["subTitle"] = "You have successfully completed review.";
                // $responseData["icon"] = "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Submit-Review/failed.png";
                $responseData["helper"] = "You can earn money for such review by enrolling yourself for paid taster program.";
            } else if ($flag["status"] == true) {
                $responseData["get_paid"] = true;
                $responseData["title"] = "Congratulations!";
                $responseData["subTitle"] = "You have successfully completed review.";
                $responseData["icon"] = "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Submit-Review/congratulation.png";
                $responseData["helper"] = "We appreciate your effort and send you a reward link to your registered email and phone number redeem it and enjoy.";
            } else if ($flag["status"] == false && isset($flag["reason"]) && $flag["reason"] == "phone") {
                $responseData["get_paid"] = true;
                $responseData["title"] = "Congratulations!";
                $responseData["subTitle"] = "You have successfully completed review.";
                $responseData["icon"] = "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Submit-Review/congratulation.png";
                $responseData["helper"] = "We appreciate your effort , But unfortunately you don't have your phone number updated. Please updated phone number and contact tagtaste to redeem it.";
            } else if ($flag["status"] == false && isset($flag["reason"]) && $flag["reason"] == "paid") {
                $responseData["get_paid"] = false;
                $responseData["title"] = "Uh Oh!";
                $responseData["subTitle"] = "You have successfully completed review.";
                $responseData["icon"] = "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Submit-Review/congratulation.png";
                $responseData["helper"] = "We appreciate your effort . Please contact tagtaste to help you with your reward status.";
            } else {
                $responseData["get_paid"] = false;
                $responseData["title"] = "Uh Oh!";
                $responseData["subTitle"] = "You have successfully completed review.";
                $responseData["icon"] = "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Submit-Review/failed.png";
                $responseData["helper"] = "We appreciate your effort , But unfortunately you missed it this time. Please try again.";
            }
        } else {
            $responseData["status"] = true;
            $responseData["is_paid"] = false;
        }
        return $responseData;
    }
    
    public function verifyPayment($paymentDetails, Request $request)
    {
        $count = PaymentLinks::where("payment_id", $paymentDetails->id)->where("status_id", "<>", config("constant.PAYMENT_CANCELLED_STATUS_ID"))->get();
        if ($count->count() < (int)$paymentDetails->user_count) {
            $getAmount = json_decode($paymentDetails->amount_json, true);
            if ($request->user()->profile->is_expert) {
                $key = "expert";
            } else {
                $key = "consumer";
            }
            $amount = ((isset($getAmount["current"][$key][0]["amount"])) ? $getAmount["current"][$key][0]["amount"] : 0);
            $data = ["amount" => $amount, "model_type" => "Public Review", "model_id" => $paymentDetails->model_id, "payment_id" => $paymentDetails->id];
            
            $createPaymentTxn = event(new TransactionInit($data));
            $paymentcount = (int)$count->count();
            if ((int)$paymentDetails->user_count == ++$paymentcount) {
                PaymentDetails::where('id', $paymentDetails->id)->update(['is_active' => 0]);
            }
            if ($createPaymentTxn) {
                return $createPaymentTxn[0];
            } else {
                Log::info("Payment Returned False" . " " . json_encode($data));
            }
        } else {
            PaymentDetails::where('id', $paymentDetails->id)->update(['is_active' => 0]);
            if ($request->has("is_paid") && $request->is_paid == true) {
                return ["status" => false, "reason" => "paid"];
            }
        }

        return ["status" => false];
    }

    public function uploadImage(Request $request, $productId)
    {
        $profileId = $request->user()->profile->id;
        $imageName = str_random("32") . ".jpg";
        $path = "images/public-review/products/$productId/profile/$profileId";
        $randnum = rand(10, 1000);
        //create a tiny image
        $path = $path . "/brand_logo/$randnum";
        $thumbnail = \Image::make($request->file('image'))->resize(320, null, function ($constraint) {
            $constraint->aspectRatio();
        })->blur(1)->stream('jpg', 70);
        \Storage::disk('s3')->put($path, (string) $thumbnail, ['visibility' => 'public']);
        $response = \Storage::url($path);
        if (!$response) {
            throw new \Exception("Could not save image " . $imageName . " at " . $path);
        }
        $this->model = $response;
        return $this->sendResponse();
    }
}
