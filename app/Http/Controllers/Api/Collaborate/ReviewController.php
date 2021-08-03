<?php

namespace App\Http\Controllers\Api\Collaborate;

use App\Collaborate\Review;
use App\Collaborate\ReviewHeader;
use App\Events\TransactionInit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use App\Payment\PaymentDetails;
use App\Payment\PaymentLinks;
use App\Profile;
use Illuminate\Support\Facades\Redis;
use App\PublicReviewProduct\Review as PublicReviewProductReview;
use Illuminate\Support\Facades\Log;

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

                    $data[] = [
                        'key' => null, 'value' => $option['value'], 'leaf_id' => $leafId,
                        'question_id' => $questionId, 'tasting_header_id' => $headerId,
                        'profile_id' => $loggedInProfileId, 'batch_id' => $batchId,
                        'collaborate_id' => $collaborateId, 'intensity' => $intensity, 'current_status' => $currentStatus, 'value_id' => $valueId,
                        'created_at' => $this->now, 'updated_at' => $this->now, 'option_type' => $optionType, 'address_map_id' => $address_id
                    ];
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
                        'created_at' => $this->now, 'updated_at' => $this->now, 'option_type' => 0, 'address_map_id' => $address_id
                    ];
                }
            }
        }
        $responseData = [];
        if (count($data) > 0) {
            $this->model = Review::insert($data);
            if($this->model==true){
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

                    $responseData = $this->paidProcessing($collaborateId, $batchId, $request);
                    
                } else {
                    $currentStatus = 2;
                    $responseData["status"] = true;
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
        return $this->sendResponse($responseData);
    }

    public function paidProcessing($collaborateId, $batchId, Request $request)
    {
        $responseData = [];
        $paymnetExist = PaymentDetails::where('model_id', $collaborateId)->where("sub_model_id", $batchId)->where('is_active', 1)->first();
        if (!empty($paymnetExist)) {
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
                $flag = $this->verifyPayment($paymnetExist, $request);
            } else {
                $flag = false;
                //check for global user rules and update euser
                $getPrivateReview = Review::where("profile_id", $request->user()->profile->id)->groupBy("collaborate_id", "batch_id")->where("current_status", 3)->get();

                $getPublicCount = PublicReviewProductReview::where("profile_id", $request->user()->profile->id)->groupBy("product_id")->where("current_status", 2)->get();

                $profile = false;

                if ($request->user()->profile->is_sensory_trained && (($getPublicCount->count() + $getPrivateReview->count()) >= config("constant.MINIMUM_PAID_TASTER_REVIEWS"))) {
                    Profile::where("id", $request->user()->profile->id)->update(["is_paid_taster" => 1]);
                    $profile = true;
                }

                if ($profile) {
                    $flag = $this->verifyPayment($paymnetExist, $request);
                }
            }
            if ($flag) {
                $responseData["title"] = "Congratulations!";
                $responseData["subTitle"] = "You have successfully completed Review.";
                $responseData["icon"] = "https://s3.ap-south-1.amazonaws.com/static4.tagtaste.com/test/modela_image.png";
                $responseData["helper"] = "We appreciate your effort and send you a reward link to your registered email and phone number redeem it and enjoy.";
            } else {
                $responseData["title"] = "Uh Oh!";
                $responseData["subTitle"] = "You have successfully completed Review.";
                $responseData["icon"] = "https://s3.ap-south-1.amazonaws.com/static4.tagtaste.com/test/modela_image.png";
                $responseData["helper"] = "We appreciate your effort , But unfortunately you are not a paid taster to earn rewards.";
            }
        } else {
            $responseData["status"] = true;
            $responseData["is_paid"] = false;
        }
        return $responseData;
    }
    public function verifyPayment($paymentDetails, Request $request)
    {
        $count = PaymentLinks::where("model_id", $paymentDetails->model_id)->where("status_id", "<>", config("constant.PAYMENT_CANCELLED_STATUS_ID"))->get();
        if ($count->count() < (int)$paymentDetails->user_count) {
            $getAmount = json_decode($paymentDetails->amount_json, true);
            if ($request->user()->profile->is_tasting_expert) {
                $key = "expert";
            } else {
                $key = "consumer";
            }
            $amount = ((isset($getAmount["current"][$key][0]["amount"])) ? $getAmount["current"][$key][0]["amount"] : 0);
            $data = ["amount" => $amount, "model_type" => "Private Review", "model_id" => $paymentDetails->model_id, "sub_model_id" => $paymentDetails->sub_model_id,"payment_id"=>$paymentDetails->id];

            $createPaymentTxn = event(new TransactionInit($data));

            if ($createPaymentTxn) {
                return true;
            } else {
                Log::info("Payment Returned False" . " " . json_encode($data));
            }
        }

        return false;
    }
}
