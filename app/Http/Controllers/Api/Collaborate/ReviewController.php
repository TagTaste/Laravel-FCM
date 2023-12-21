<?php

namespace App\Http\Controllers\Api\Collaborate;

use App\Collaborate;
use App\Collaborate\Applicant;
use App\Collaborate\Review;
use App\Collaborate\ReviewHeader;
use App\Company;
use App\Events\TransactionInit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use App\Payment\PaymentDetails;
use App\Payment\PaymentLinks;
use App\PaymentHelper;
use App\Profile;
use Illuminate\Support\Facades\Redis;
use App\PublicReviewProduct\Review as PublicReviewProductReview;
use Illuminate\Support\Facades\Log;
use App\CollaborateTastingEntryMapping;
use App\Applicant;

class ReviewController extends Controller
{

    protected $model;
    protected $now;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Review $model)
    {
        $this->model = $model;
        $this->now = Carbon::now()->toDateTimeString();
    }

    public function startReview(Request $request, $collaborateId, $batchId){
        // begin transaction
        \DB::beginTransaction();
        try {
            $this->model = false;
            $profileId = $request->user()->profile->id;

            $checkAssign = \DB::table('collaborate_batches_assign')->where('batch_id', $batchId)->where('profile_id', $profileId)->exists();

            if (!$checkAssign) {
                return $this->sendNewError("Wrong product assigned");
            }
            $latestCurrentStatus = Redis::get("current_status:batch:$batchId:profile:$profileId");
            if ($latestCurrentStatus == 3) {
                return $this->sendNewError("You have already completed this product");
            }

            CollaborateTastingEntryMapping::create(["profile_id"=>$profileId, "collaborate_id"=>$collaborateId, "batch_id"=>$batchId, "activity"=>config("constant.REVIEW_ACTIVITY.START")]);

            $this->model = true;
            \DB::commit();
        } catch (\Exception $e) {
            // roll in case of error
            \DB::rollback();
            \Log::info($e->getMessage());
            $this->model = null;
            return $this->sendNewError($e->getMessage());
        }
        
        return $this->sendNewResponse();
    }

    public function reviewAnswers(Request $request, $collaborateId, $headerId)
    {
        $this->now = Carbon::now()->toDateTimeString();
        $data = [];
        $answers = $request->input('answer');
        $loggedInProfileId = $request->user()->profile->id;
        $batchId = $request->input('batch_id');

        if (
            !$request->has('address_id') &&
            \App\Collaborate\Addresses::where('collaborate_id', $collaborateId)->Where('outlet_id', !null)->exists()
        ) {
            $this->model = ["status" => false];
            return $this->sendError('Please send the respective outlet (address id) as query parameter');
        }

        // if(!$request->has('address_id') && 
        // \App\Collaborate::where('id',$collaborateId)->first()->track_consistency){
        // return $this->sendError('Please send the respective outlet (address id) as query parameter');
        // } 
        else if (
            $request->has('address_id') &&
            !\App\Collaborate\Addresses::where('collaborate_id', $collaborateId)->where('address_id', $request->address_id)->exists()
        ) {
            $this->model = ["status" => false];
            return $this->sendError('Invalid Address id');
        } else {
            $address_id = $request->address_id != null ? $request->address_id : null;
        }

        if (!$request->has('batch_id')) {
            $this->model = ["status" => false];
            return $this->sendError("No prodcut id found");
        }
        $checkAssign = \DB::table('collaborate_batches_assign')->where('batch_id', $batchId)->where('profile_id', $loggedInProfileId)->exists();

        if (!$checkAssign) {
            $this->model = ["status" => false];
            return $this->sendError("Wrong product assigned");
        }
        $currentStatus = $request->has('current_status') ? $request->input('current_status') : 2;
        $latestCurrentStatus = Redis::get("current_status:batch:$batchId:profile:$loggedInProfileId");
        if ($currentStatus == $latestCurrentStatus && $latestCurrentStatus == 3) {
            $this->model = ["status" => false];
            return $this->sendError("You have already completed this product");
        }
        $this->model = Review::where('profile_id', $loggedInProfileId)->where('collaborate_id', $collaborateId)
            ->where('batch_id', $batchId)->where('tasting_header_id', $headerId)->delete();

        if (count($answers)) {
            foreach ($answers as $answer) {
                $options = isset($answer['option']) ? $answer['option'] : [];
                $questionId = $answer['question_id'];
                $selectType = isset($answer['select_type']) ? $answer['select_type'] : null;
                if (isset($answer["option"])) {
                    $optionVal = \DB::table('collaborate_tasting_questions')->where('id', $questionId)->get();
                    if (!isset(json_decode($optionVal[0]->questions)->nested_option_list))
                        $optionVal = json_decode($optionVal[0]->questions)->option;
                    else
                        $optionVal = "AROMA";
                }
                foreach ($options as $option) {
                    $leafId = isset($option['id']) && $option['id'] != 0 ? $option['id'] : null;
                    if ($optionVal == 'AROMA') {
                        $optionType = \DB::table('collaborate_tasting_nested_options')->where('id', $leafId)->first()->option_type;
                    } else {
                        $optionType = isset($optionVal[$leafId - 1]->option_type) ? $optionVal[$leafId - 1]->option_type : 0;
                    }
                    //$optionType = isset($option['option_type'])? $option['option_type'] : 0;
                    $valueId = isset($option['value_id']) && $option['value_id'] != 0 ? $option['id'] : null;
                    $intensity = isset($option['intensity']) && !is_null($option['intensity']) && !empty($option['intensity']) ? $option['intensity'] : null;

                    //Added by nikhil to decode &amp to & or anyother other encoding issue
                    $intensity = html_entity_decode($intensity);
                    if($selectType == config("constant.SELECT_TYPES.RANGE_TYPE")){
                        $data[] = [
                            'key' => null,'value_id' => $option['value'],'value' => $option['label'], 'leaf_id' => $leafId,
                            'question_id' => $questionId, 'tasting_header_id' => $headerId,
                            'profile_id' => $loggedInProfileId, 'batch_id' => $batchId,
                            'collaborate_id' => $collaborateId, 'intensity' => $intensity, 'current_status' => $currentStatus,
                            'created_at' => $this->now, 'updated_at' => $this->now, 'option_type' => $optionType, 'meta' => null, 'address_map_id' => $address_id
                        ];    
                    }else if($selectType == config("constant.SELECT_TYPES.RANK_TYPE")){
                        $data[] = [
                            'key' => null, 'value' => $option['value'],'value_id'=>$option['rank'], 'leaf_id' => $leafId,
                            'question_id' => $questionId, 'tasting_header_id' => $headerId,
                            'profile_id' => $loggedInProfileId, 'batch_id' => $batchId,
                            'collaborate_id' => $collaborateId, 'intensity' => $intensity, 'current_status' => $currentStatus,
                            'created_at' => $this->now, 'updated_at' => $this->now, 'option_type' => $optionType, 'meta' => null, 'address_map_id' => $address_id
                        ];
                    }else{
                        $data[] = [
                            'key' => null, 'value' => $option['value'], 'leaf_id' => $leafId,
                            'question_id' => $questionId, 'tasting_header_id' => $headerId,
                            'profile_id' => $loggedInProfileId, 'batch_id' => $batchId,
                            'collaborate_id' => $collaborateId, 'intensity' => $intensity, 'current_status' => $currentStatus, 'value_id' => $valueId,
                            'created_at' => $this->now, 'updated_at' => $this->now, 'option_type' => $optionType, 'meta' => null, 'address_map_id' => $address_id
                        ];    
                    }

                }
                if (isset($answer['meta']) && !is_null($answer['meta']) && !empty($answer['meta'])) {
                    if (isset($answer['track_consistency']) && $answer['track_consistency']) {
                        \DB::table('collaborate_batches_assign')
                            ->where('batch_id', $batchId)
                            ->where('profile_id', $loggedInProfileId)
                            ->update(['bill_verified' => 1]);
                    }
                    $data[] = [
                        'key' => "authenticity_check", 'value' => "meta", 'leaf_id' => 0,
                        'question_id' => $questionId, 'tasting_header_id' => $headerId,
                        'profile_id' => $loggedInProfileId, 'batch_id' => $batchId, 'collaborate_id' => $collaborateId, 'intensity' => null,
                        'current_status' => $currentStatus, 'value_id' => null,
                        'created_at' => $this->now, 'updated_at' => $this->now, 'meta' => $answer['meta'], 'option_type' => 0, 'address_map_id' => $address_id
                    ];
                }
                if (isset($answer['comment']) && !is_null($answer['comment']) && !empty($answer['comment'])) {
                    $data[] = [
                        'key' => "comment", 'value' => $answer['comment'], 'leaf_id' => 0,
                        'question_id' => $questionId, 'tasting_header_id' => $headerId,
                        'profile_id' => $loggedInProfileId, 'batch_id' => $batchId,
                        'collaborate_id' => $collaborateId, 'intensity' => null, 'current_status' => $currentStatus, 'value_id' => null,
                        'created_at' => $this->now, 'updated_at' => $this->now, 'option_type' => 0, 'meta' => null, 'address_map_id' => $address_id
                    ];
                }
            }
        }
        $responseData = [];
        $responseData["status"] = true;
        if (count($data) > 0) {
            $this->model = Review::insert($data);
            if ($this->model == true) {
                $responseData["status"] = true;
            }
            if ($currentStatus == 3) {
                $mandatoryQuestion = \DB::table('collaborate_tasting_questions')->where('collaborate_id', $collaborateId)
                    ->where('is_mandatory', 1)->where('is_nested_question', 0)->get();
                $mandatoryQuestionsId = $mandatoryQuestion->pluck('id');
                $mandatoryReviewCount = \DB::table('collaborate_tasting_user_review')->where('collaborate_id', $collaborateId)->whereIn('question_id', $mandatoryQuestionsId)->where('batch_id', $batchId)->where('profile_id', $loggedInProfileId)->distinct('question_id')->count('question_id');

                if ($mandatoryQuestion->count() == $mandatoryReviewCount) {

                    \DB::table('collaborate_tasting_user_review')->where('collaborate_id', $collaborateId)
                        ->where('batch_id', $batchId)->where('profile_id', $loggedInProfileId)->update(['current_status' => 3]);
                } else {
                    $currentStatus = 2;

                    \DB::table('collaborate_tasting_user_review')->where('collaborate_id', $collaborateId)
                        ->where('batch_id', $batchId)->where('profile_id', $loggedInProfileId)->update(['current_status' => 2]);

                    \Redis::set("current_status:batch:$batchId:profile:$loggedInProfileId", $currentStatus);

                    $missingHeaders = $this->getMissingHeaders($collaborateId, $batchId, $loggedInProfileId);

                    $this->model = ["status" => false];
                    return $this->sendError("Mandatory questions missing in ".$missingHeaders);

                }
            }
            if ($latestCurrentStatus == 1) {
                \DB::table('collaborate_batches_assign')
                    ->where('batch_id', $batchId)
                    ->where('profile_id', $loggedInProfileId)
                    ->update(['address_id' => $address_id]);
            }
            \Redis::set("current_status:batch:$batchId:profile:$loggedInProfileId", $currentStatus);
        }

        //update the entry mapping
        $headerName = \DB::table('collaborate_tasting_header')->where('id', $headerId)->first();

        if($currentStatus == 3){
            CollaborateTastingEntryMapping::create(["profile_id"=>$loggedInProfileId, "collaborate_id"=>$collaborateId, "batch_id"=>$batchId, "header_id"=>$headerId, "header_title"=>$headerName->header_type,"activity"=>config("constant.REVIEW_ACTIVITY.END")]);
        }else{
            CollaborateTastingEntryMapping::create(["profile_id"=>$loggedInProfileId, "collaborate_id"=>$collaborateId, "batch_id"=>$batchId, "header_id"=>$headerId, "header_title"=>$headerName->header_type, "activity"=>config("constant.REVIEW_ACTIVITY.SECTION_SUBMIT")]);
        }


        if ($this->model && $currentStatus == 3) {
            $responseData = $this->paidProcessing($collaborateId, $batchId, $request);
        }
        return $this->sendResponse($responseData);
    }
    
    protected function getMissingHeaders($collaborateId, $batchId, $profileId){
        $filledQuestionIds = \DB::table('collaborate_tasting_user_review')
        ->where('collaborate_id', $collaborateId)
        ->where('batch_id', $batchId)
        ->where('profile_id', $profileId)->get();

        $missingHeaderIds = \DB::table('collaborate_tasting_questions')
        ->where('collaborate_id', $collaborateId)
        ->where('is_mandatory',1)
        ->whereNotIn('id', $filledQuestionIds->pluck('question_id'))->get();

        $headerList = \DB::table('collaborate_tasting_header')
        ->whereIn('id', $missingHeaderIds->pluck('header_type_id'))->get()->toArray();
        $missingHeaders = '';
        
        foreach ($headerList as $header) {
            $missingHeaders = $missingHeaders.$header->header_type.', ';
        }

        if (strlen($missingHeaders) > 0){
            $missingHeaders = substr($missingHeaders, 0, strlen($missingHeaders)-2);
        }

        $missingHeaders = substr_replace($missingHeaders, ' and', strrpos($missingHeaders, ','), 1);
        return $missingHeaders;

    }


    public function paidProcessing($collaborateId, $batchId, Request $request)
    {
        $responseData = $flag = [];
        $requestPaid = $request->is_paid ?? false;
        $paymnetExist = PaymentDetails::where('model_id', $collaborateId)->where("sub_model_id", $batchId)->where('is_active', 1)->first();
        if (!empty($paymnetExist) || $requestPaid) {
            $responseData["status"] = true;
            $responseData["is_paid"] = true;
            if ($requestPaid) {
                $flag = ["status" => false, "reason" => "paid"];
            }
            //check for paid user
            // if (empty($request->user()->profile->phone)) {
            //     $responseData["title"] = "Uh Oh!";
            //     $responseData["subTitle"] = "Please Contact Admin.";
            //     $responseData["icon"] = "https://s3.ap-south-1.amazonaws.com/static4.tagtaste.com/test/modela_image.png";
            //     $responseData["helper"] = "Phone number not updated";
            // } else
            $exp = (($paymnetExist != null && !empty($paymnetExist->excluded_profiles)) ? $paymnetExist->excluded_profiles : null);

            if ($exp != null) {
                $separate = explode(",", $exp);
                if (in_array($request->user()->profile->id, $separate)) {
                    //excluded profile error to be updated
                    $responseData["is_paid"] = false;
                    return $responseData;
                }
            }

            if ($request->user()->profile->is_paid_taster) {
                //check for count and amount (payment details)
                $profile = true;
                $flag["status"] = false;
            } else {
                $flag["status"] = false;
                //check for global user rules and update euser
                $getPrivateReview = Review::where("profile_id", $request->user()->profile->id)->groupBy("collaborate_id", "batch_id")->where("current_status", 3)->get();

                $getPublicCount = PublicReviewProductReview::where("profile_id", $request->user()->profile->id)->groupBy("product_id")->where("current_status", 2)->get();

                $profile = false;

                if ($request->user()->profile->is_sensory_trained && (($getPublicCount->count() + $getPrivateReview->count()) >= config("constant.MINIMUM_PAID_TASTER_REVIEWS"))) {
                    Profile::where("id", $request->user()->profile->id)->update(["is_paid_taster" => 1]);
                    $profile = true;
                }
            }
            $request->merge(["collaborate_id" => $collaborateId, "batch_id" => $batchId]);
            if ($profile && $paymnetExist != null) {

                $flag = $this->verifyPayment($paymnetExist, $request);
            }

            $responseData['is_paid_taster'] = $profile;
            if (!$profile) {
                $responseData["get_paid"] = false;
                // $responseData["title"] = "Uh Oh!";
                // $responseData["subTitle"] = "You have successfully completed the review.";
                // $responseData["icon"] = "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Submit-Review/failed.png";
                $responseData["helper"] = "You can earn money for such review by enroling yourself in paid taster program.";
            } else if ($flag["status"] == true) {
                $responseData["get_paid"] = true;
                $responseData["title"] = "Congratulations!";
                $responseData["subTitle"] = "You have successfully completed the review.";
                $responseData["icon"] = "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Submit-Review/congratulation.png";
                $responseData["helper"] = "We appreciate your effort and have sent you a reward link to your registered email and phone number, redeem it and enjoy.";
            } else if ($flag["status"] == false && isset($flag["reason"]) && $flag["reason"] == "phone") {
                $responseData["get_paid"] = true;
                $responseData["title"] = "Congratulations!";
                $responseData["subTitle"] = "You have successfully completed the review.";
                $responseData["icon"] = "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Submit-Review/congratulation.png";
                $responseData["helper"] = "We appreciate your effort, but unfortunately you don't have your phone number updated in your profile. Please update phone number and contact us at payment@tagtaste.com to redeem the reward.";
            } else if ($flag["status"] == false && isset($flag["reason"]) && $flag["reason"] == "paid") {
                $responseData["get_paid"] = false;
                $responseData["title"] = "Uh Oh!";
                $responseData["subTitle"] = "You have successfully completed the review.";
                $responseData["icon"] = "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Submit-Review/congratulation.png";
                $responseData["helper"] = "We appreciate your effort but unfortunately you missed the reward. Please contact us at payment@tagtaste.com for any further help.";
            } else if ($flag["status"] == false && isset($flag["reason"]) && $flag["reason"] == "not_paid") {
                $responseData["status"] = true;
                $responseData["is_paid"] = false;
            } else {
                $responseData["get_paid"] = false;
                $responseData["title"] = "Uh Oh!";
                $responseData["subTitle"] = "You have successfully completed the review.";
                $responseData["icon"] = "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Submit-Review/failed.png";
                $responseData["helper"] = "We appreciate your effort but unfortunately you missed the reward. Please contact us at payment@tagtaste.com for any further help.";
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

            $amount = 0;
            if ($paymentDetails->review_type == config("payment.PAYMENT_REVIEW_TYPE.REVIEW_COUNT")) {


                $amount = ((isset($getAmount["current"]['taster'][0]["amount"])) ? $getAmount["current"]['taster'][0]["amount"] : 0);
            } else if ($paymentDetails->review_type == config("payment.PAYMENT_REVIEW_TYPE.USER_TYPE")) {

                $getCount = PaymentHelper::getDispatchedPaymentUserTypes($paymentDetails);

                if ($request->user()->profile->is_expert) {
                    $key = "expert";
                } else {
                    $key = "consumer";
                }
                
                if ($getCount[$key] >= $getAmount["current"][$key][0]["user_count"]) {
                    //error message for different user type counts exceeded
                    return ["status" => false, "reason" => "not_paid"];
                }

                $amount = ((isset($getAmount["current"][$key][0]["amount"])) ? $getAmount["current"][$key][0]["amount"] : 0);
            }

            $applicant = Applicant::where("collaborate_id", $paymentDetails->model_id)->where("profile_id", $request->user()->profile->id)->first();

            $data = ["amount" => $amount, "tds_deduction"=>$request->user()->profile->tds_deduction, "model_type" => "Private Review", "model_id" => $paymentDetails->model_id, "sub_model_id" => $paymentDetails->sub_model_id, "payment_id" => $paymentDetails->id, "is_donation"=> $applicant->is_donation, "donation_organisation_id"=> $applicant->donation_organisation_id];

            if (isset($paymentDetails->comment) && !empty($paymentDetails->comment)) {
                $data["comment"] = $paymentDetails->comment;
            }

            $createPaymentTxn = event(new TransactionInit($data));
            $paymentcount = (int)$count->count();
            if ((int)$paymentDetails->user_count == ++$paymentcount) {
                PaymentDetails::where('id', $paymentDetails->id)->update(["is_active" => 0]);
            }
            if ($createPaymentTxn) {
                return $createPaymentTxn[0];
            } else {
                Log::info("Payment Returned False" . " " . json_encode($data));
            }
        } else {
            PaymentDetails::where('id', $paymentDetails->id)->update(["is_active" => 0]);
            if ($request->has("is_paid") && $request->is_paid == true) {
                return ["status" => false, "reason" => "paid"];
            }
        }

        return ["status" => false];
    }

    public function getReviews($profileId)
    {

        $page = request()->input('page');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);
        $query = Review::join("collaborates", "collaborates.id", "collaborate_tasting_user_review.collaborate_id")->selectRaw('count(DISTINCT batch_id) as count,collaborate_id,title,images_meta,collaborates.profile_id,company_id')->where("collaborate_tasting_user_review.profile_id", $profileId)->where("current_status", 3)->where("collaborate_type", "product-review")->groupBy("collaborate_id")->orderBy("collaborate_tasting_user_review.updated_at", "desc");
        $productUserReviewed = $query->skip($skip)->take($take)
        ->get();

        if (empty($productUserReviewed)) {
            $this->sendNewError("User Has not participated in any product reviews");
        }

        $data["collaborates"] = [];
        foreach ($productUserReviewed as $reviewedProduct) {
            
            $reviewedProduct->images_meta = json_decode($reviewedProduct->images_meta);
            $collaborate["id"] = $reviewedProduct->collaborate_id;
            $collaborate["title"] = $reviewedProduct->title;
            $collaborate["images_meta"] = $reviewedProduct->images_meta;
            $collaborate["profile"] = $reviewedProduct->profile;
            $collaborate["company"] =  isset($reviewedProduct->company)?$reviewedProduct->company:null;

            $item["collaboration"] = $collaborate;
            $item["meta"]["total_product_reviewed"] = $reviewedProduct->count;
            $data["collaborates"][] = $item;
        }

        return $this->sendNewResponse($data);
    }
}
