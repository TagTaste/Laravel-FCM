<?php

namespace App\Http\Controllers\APi\PublicReviewProduct;

use App\PublicReviewProduct;
use App\PublicReviewProduct\Review;
use App\PublicReviewProduct\ReviewHeader;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\File;


class ReportController extends Controller
{


    public function reportHeaders(Request $request, $id)
    {
        $product = PublicReviewProduct::where('id',$id)->first();
        if($product == null)
        {
            return $this->sendError("No product exists");
        }
        $header = ReviewHeader::where('header_selection_type','=',2)->where('is_active',1)->where('global_question_id',$product->global_question_id)
            ->orderBy('id')->first();
        $questions = PublicReviewProduct\Questions::where('header_id',$header->id)->whereNotIn('questions->select_type',[3,5])->get();
        if(is_null($questions) || empty($questions) || $questions->count() == 0)
            $this->model = ReviewHeader::where('is_active',1)->whereIn('header_selection_type',[1])->where('global_question_id',$product->global_question_id)
                ->orderBy('id')->get();
        else
            $this->model = ReviewHeader::where('is_active',1)->whereIn('header_selection_type',[1,2])->where('global_question_id',$product->global_question_id)
                ->orderBy('id')->get();

        return $this->sendResponse();
    }

    public function reportSummary(Request $request,$productId)
    {
        $product = PublicReviewProduct::where('id',$productId)->first();
        if($product == null)
        {
            return $this->sendError("Product is not available");
        }
        $count = Review::where('product_id',$productId)->where('current_status',2)->distinct('profile_id')->count('profile_id');
        if($count < 1)
        {
            $this->model = [];
            $this->model['title'] = 'Sensogram';
            $this->model['description'] = 'The following chart depicts the Tasters’ overall product preference and attribute-wise rating on a 7-point scale.';
            $this->model['header_rating'] = null;
            $this->model['self_review'] = $this->getSelfReview($product,$request->user()->profile->id);
            return $this->sendResponse();
        }
        $this->model = [];
        $this->model['title'] = 'Sensogram';
        $this->model['description'] = 'The following chart depicts the Tasters’ overall product preference and attribute-wise rating on a 7-point scale.';
//        $this->model['info'] = ['text'=>'this is text','link'=>null,'images'=>[]];
        $this->model['header_rating'] = $this->getHeaderRating($product);
        $this->model['self_review'] = $this->getSelfReview($product,$request->user()->profile->id);
        return $this->sendResponse();
    }

    public function getSelfReview($product,$loggedInProfileId)
    {
        $productId = $product->id;
        $header = ReviewHeader::where('global_question_id',$product->global_question_id)->where('header_selection_type',2)->first();
        $review = Review::where('product_id',$productId)->where('header_id',$header->id)->where('profile_id',$loggedInProfileId)
            ->where('select_type',5)->first();
        return $review;
    }

    public function getHeaderRating($product)
    {
        $globalQuestionId = $product->global_question_id;
        $headers = ReviewHeader::where('global_question_id',$globalQuestionId)->where('header_selection_type',1)->get();
        $productId = $product->id;
        $overallPreferances = \DB::table('public_product_user_review')->where('product_id',$productId)->where('current_status',2)->where('select_type',5)->get();

        $headerRating = [];
        foreach ($headers as $header)
        {
            $userCount = 0;
            $headerRatingSum = 0;
            foreach ($overallPreferances as $overallPreferance)
            {
                if($overallPreferance->header_id == $header->id)
                {
                    $headerRatingSum += $overallPreferance->leaf_id;
                    $userCount++;
                }
            }
            $headerRating[] = ['header_type'=>$header->header_type,'meta'=>$this->getRatingMeta($userCount,$headerRatingSum,$header->id)];
        }

        return $headerRating;

    }

    protected function getRatingMeta($userCount,$headerRatingSum,$headerId)
    {
        $meta = [];
        $question = \DB::table('public_review_questions')->where('header_id',$headerId)->where('questions->select_type',5)->first();
        $question = json_decode($question->questions);
        $option = isset($question->option) ? $question->option : [];
        $meta['max_rating'] = count($option);
        $meta['overall_rating'] = $userCount > 0 ? $headerRatingSum/$userCount : 0.00;
        $meta['count'] = $userCount;
        $meta['color_code'] = $this->getColorCode(floor($meta['overall_rating']));
        return $meta;
    }

    protected function getColorCode($value)
    {
        if($value == 0 || is_null($value))
            return null;
        switch ($value) {
            case 1:
                return '#8C0008';
                break;
            case 2:
                return '#D0021B';
                break;
            case 3:
                return '#C92E41';
                break;
            case 4:
                return '#E27616';
                break;
            case 5:
                return '#AC9000';
                break;
            case 6:
                return '#7E9B42';
                break;
            case 7:
                return '#577B33';
                break;
            default:
                return '#305D03';
        }
    }
    
    public function reports(Request $request,$productId,$headerId)
    {
        $product = PublicReviewProduct::where('id',$productId)->first();

        if ($product === null) {
            return $this->sendError("Invalid product.");
        }

        $headerInfo = ReviewHeader::where('id',$headerId)->first();

        if($headerInfo->header_selection_type == 1)
        {
            $withoutNest = \DB::table('public_review_questions')->where('global_question_id',$product->global_question_id)
                ->whereNull('parent_question_id')->where('questions->select_type','!=',5)->where('header_id',$headerId)->where('is_active',1)->orderBy('id')->get();
            $withNested = \DB::table('public_review_questions')->where('global_question_id',$product->global_question_id)
                ->whereNotNull('parent_question_id')->where('questions->select_type','!=',5)->where('is_active',1)->where('header_id',$headerId)->orderBy('id')->get();
        }
        else
        {
            $withoutNest = \DB::table('public_review_questions')->where('global_question_id',$product->global_question_id)
                ->whereNull('parent_question_id')->whereNotIn('questions->select_type',[3,5])->where('header_id',$headerId)->where('is_active',1)->orderBy('id')->get();
            $withNested = \DB::table('public_review_questions')->where('global_question_id',$product->global_question_id)
                ->whereNotNull('parent_question_id')->where('questions->select_type',[3,5])->where('is_active',1)->where('header_id',$headerId)->orderBy('id')->get();
        }

        foreach ($withoutNest as &$data)
        {
            if(isset($data->questions)&&!is_null($data->questions))
            {
                $data->questions = json_decode($data->questions);
            }
        }
        foreach ($withoutNest as &$data)
        {
            $i = 0;
            foreach ($withNested as $item)
            {
                if($item->parent_question_id == $data->id)
                {
                    $item->questions = json_decode($item->questions);
                    $item->questions->id = $item->id;
                    $item->questions->is_nested_question = $item->is_nested_question;
                    $item->questions->is_mandatory = $item->is_mandatory;
                    $item->questions->is_active = $item->is_active;
                    $item->questions->parent_question_id = $item->parent_question_id;
                    $item->questions->header_id = $item->header_id;
                    $item->questions->global_question_id = $item->global_question_id;
                    $data->questions->questions{$i} = $item->questions;
                    $i++;
                }
            }
        }

        //filters data and bht bekar likha hua h so isko thik krne me bht time lagega and time h nhi so sorry :(
//        $filters = $request->input('filters');
//        $resp = $this->getFilterProfileIds($filters);
//        $profileIds = $resp['profile_id'];
//        $type = $resp['type'];
//        $boolean = 'and' ;
        $totalApplicants = \DB::table('public_product_user_review')->where('value','!=','')->where('current_status',2)->where('product_id',$productId)->distinct()->get(['profile_id'])->count();
        $model = [];
        foreach ($withoutNest as $data)
        {
            $reports = [];
            if(isset($data->questions)&&!is_null($data->questions))
            {
                $reports['question_id'] = $data->id;
                $reports['title'] = $data->title;
                $reports['subtitle'] = $data->subtitle;
                $reports['is_nested_question'] = $data->is_nested_question;
                $reports['question'] = $data->questions ;
                if(isset($data->questions->is_nested_question) && $data->questions->is_nested_question == 1)
                {
                    $subAnswers = [];
                    foreach ($data->questions->questions as $item)
                    {
                        $subReports = [];
                        $subReports['question_id'] = $item->id;
                        $subReports['title'] = $item->title;
                        $subReports['subtitle'] = isset($item->subtitle) ? $item->subtitle : null;
                        $subReports['is_nested_question'] = $item->is_nested_question;
                        $subReports['total_applicants'] = $totalApplicants;
                        $subReports['select_type'] = isset($item->questions->select_type) ? $item->questions->select_type : null;
                        $subReports['total_answers'] = \DB::table('public_product_user_review')->where('current_status',2)->where('product_id',$productId)
                            ->where('question_id',$item->id)->distinct()->get(['profile_id'])->count();
                        $answers = \DB::table('public_product_user_review')->select('leaf_id','value',\DB::raw('count(*) as total'),'option_type','id')->selectRaw("GROUP_CONCAT(intensity) as intensity")->where('current_status',2)
                            ->where('product_id',$productId)->where('question_id',$item->id)
                            ->orderBy('question_id','ASC')->orderBy('total','DESC')->groupBy('question_id','leaf_id','option_type','id')->get();
                        $options = isset($item->option) ? $item->option : [];

                        foreach ($answers as &$answer)
                        {
                            if($answer->option_type == 1) {
                                $answer->value = 'Any other';
                            } else if ($answer->option_type == 2) {
                                $answer->value = 'None';
                            } else {
                                $answer->value = \DB::table('public_product_user_review')->select('value')->where('id',$answer->id)->get();
                            }
                            $value = [];
                            foreach ($options as $option)
                            {
                                if($option->id == $answer->leaf_id)
                                {
                                    if($option->is_intensity == 1 && $option->intensity_type == 2)
                                    {
                                        $answerIntensity = $answer->intensity;
                                        $answerIntensity = explode(",",$answerIntensity);
                                        $questionIntensity = $option->intensity_value;
                                        $questionIntensity = explode(",",$questionIntensity);
                                        foreach ($questionIntensity as $x)
                                        {
                                            $count = 0;
                                            foreach ($answerIntensity as $y)
                                            {
                                                if($this->checkValue($x,$y))
                                                    $count++;
                                            }
                                            $value[] = ['value'=>$x,'count'=>$count];
                                        }
                                    }
                                    else if($option->is_intensity == 1 && $option->intensity_type == 1)
                                    {
                                        $answerIntensity = $answer->intensity;
                                        $answerIntensity = explode(",",$answerIntensity);
                                        $questionIntensityValue = $option->intensity_value;
                                        $questionIntensity = [];
                                        for($i = 1; $i <=(int)$questionIntensityValue ; $i++)
                                        {
                                            $questionIntensity[] = $i;
                                        }
                                        foreach ($questionIntensity as $x)
                                        {
                                            $count = 0;
                                            foreach ($answerIntensity as $y)
                                            {
                                                if($y == $x)
                                                    $count++;
                                            }
                                            $value[] = ['value'=>$x,'count'=>$count];
                                        }
                                    }
                                    $answer->is_intensity = isset($option->is_intensity) ? $option->is_intensity : null;
                                    $answer->intensity_value = isset($option->intensity_value) ? $option->intensity_value : null;
                                    $answer->intensity_type = isset($option->intensity_type) ? $option->intensity_type : null;
                                }
                            }
                            $answer->intensity = $value;
                        }
                        $subReports['answer'] = $answers;
                        $subAnswers[] = $subReports;
                    }
                    $reports['nestedAnswers'] = $subAnswers;

                }
                else
                    unset($reports['nestedAnswers']);
                $reports['total_applicants'] = $totalApplicants;
                $reports['total_answers'] = \DB::table('public_product_user_review')->where('current_status',2)->where('product_id',$productId)
                    ->where('question_id',$data->id)->distinct()->get(['profile_id'])->count();
                $reports['select_type'] = isset($data->questions->select_type) ? $data->questions->select_type : null;
                if(isset($data->questions->select_type) && $data->questions->select_type == 3)
                {
                    $reports['answer'] = Review::where('product_id',$productId)->where('question_id',$data->id)
                        ->where('current_status',2)->where('header_id',$headerId)->skip(0)->take(3)->get();
                }
                else
                {
                    $answers = \DB::table('public_product_user_review')->select('id','leaf_id',\DB::raw('count(*) as total'),'option_type')->selectRaw("GROUP_CONCAT(intensity) as intensity")->where('current_status',2)
                        ->where('product_id',$productId)->where('question_id',$data->id)
                        ->orderBy('question_id','ASC')->orderBy('total','DESC')->groupBy('leaf_id','question_id','value','option_type','id')->get();
                    $options = isset($data->questions->option) ? $data->questions->option : [];
                    foreach ($answers as &$answer)
                    {
                        if($answer->option_type == 1) {
                            $answer->value = 'Any other';
                        } else if ($answer->option_type == 2) {
                            $answer->value = 'None';
                        } else {
                            $answer->value = \DB::table('public_product_user_review')->select('value')->where('id',$answer->id)->first().value;
                        }
                        $value = [];
                        if(isset($data->questions->is_nested_option) && $data->questions->is_nested_option == 1 && isset($data->questions->intensity_value) && isset($answer->intensity))
                        {
                            if($data->questions->intensity_type == 2)
                            {
                                $answerIntensity = $answer->intensity;
                                $answerIntensity = explode(",",$answerIntensity);
                                $questionIntensity = $data->questions->intensity_value;
                                $questionIntensity = explode(",",$questionIntensity);
                                foreach ($questionIntensity as $x)
                                {

                                    $count = 0;
                                    foreach ($answerIntensity as $y)
                                    {
                                        if($this->checkValue($x,$y))
                                            $count++;
                                    }
                                    $value[] = ['value'=>$x,'count'=>$count];
                                }
                            }
                            else if($data->questions->intensity_type == 1)
                            {
                                $answerIntensity = $answer->intensity;
                                $answerIntensity = explode(",",$answerIntensity);
                                $questionIntensityValue = $data->questions->intensity_value;
                                $questionIntensity = [];
                                for($i = 1; $i <=(int)$questionIntensityValue ; $i++)
                                {
                                    $questionIntensity[] = $i;
                                }
                                foreach ($questionIntensity as $x)
                                {
                                    $count = 0;
                                    foreach ($answerIntensity as $y)
                                    {
                                        if($y == $x)
                                            $count++;
                                    }
                                    $value[] = ['value'=>$x,'count'=>$count];
                                }
                            }
                            $answer->is_intensity = isset($data->questions->is_intensity) ? $data->questions->is_intensity : null;
                            $answer->intensity_value = $data->questions->intensity_value;
                            $answer->intensity_type = $data->questions->intensity_type;
                        }
                        else
                        {
                            foreach ($options as $option)
                            {
                                if($option->id == $answer->leaf_id)
                                {
                                    if($option->is_intensity == 1 && $data->questions->select_type != 5 && $option->intensity_type == 2)
                                    {
                                        $answerIntensity = $answer->intensity;
                                        $answerIntensity = explode(",",$answerIntensity);
                                        $questionIntensity = $option->intensity_value;
                                        $questionIntensity = explode(",",$questionIntensity);
                                        foreach ($questionIntensity as $x)
                                        {
                                            $count = 0;
                                            foreach ($answerIntensity as $y)
                                            {
                                                if($this->checkValue($x,$y))
                                                    $count++;
                                            }
                                            $value[] = ['value'=>$x,'count'=>$count];
                                        }
                                    }
                                    else if($option->is_intensity == 1 && $data->questions->select_type != 5 && $option->intensity_type == 1)
                                    {
                                        $answerIntensity = $answer->intensity;
                                        $answerIntensity = explode(",",$answerIntensity);
                                        $questionIntensityValue = $option->intensity_value;
                                        $questionIntensity = [];
                                        for($i = 1; $i <=(int)$questionIntensityValue ; $i++)
                                        {
                                            $questionIntensity[] = $i;
                                        }
                                        foreach ($questionIntensity as $x)
                                        {
                                            $count = 0;
                                            foreach ($answerIntensity as $y)
                                            {
                                                if($y == $x)
                                                    $count++;
                                            }
                                            $value[] = ['value'=>$x,'count'=>$count];
                                        }
                                    }
                                    $answer->is_intensity = isset($option->is_intensity) ? $option->is_intensity : null;
                                    $answer->intensity_value = isset($option->intensity_value) ? $option->intensity_value : null;
                                    $answer->intensity_type = isset($option->intensity_type) ? $option->intensity_type : null;
                                }

                            }
                        }
                        $answer->intensity = $value;

                    }

                    $reports['answer'] = $answers;
                }

                if(isset($data->questions->is_nested_option))
                {
                    $reports['is_nested_option'] = $data->questions->is_nested_option;
                    if($data->questions->is_nested_option == 1)
                    {
                        foreach($reports['answer'] as &$item)
                        {
                            $nestedOption = \DB::table('public_review_nested_options')->where('header_id',$headerId)
                                ->where('question_id',$data->id)->where('id',$item->leaf_id)->where('value','like',$item->value)->first();
                            $item->path = isset($nestedOption->path) ? $nestedOption->path : null;
                            $item->is_intensity = isset($nestedOption->is_intensity) ? $nestedOption->is_intensity : null;
                        }
                    }
                }

                $model[] = $reports;
            }
        }
        $this->model['users_review'] = $model;
        $this->model['users_rating'] = $this->getUsersRating($productId,$headerId);
        return $this->sendResponse();
    }

    public function getUsersRating($productId,$headerId)
    {
        $overallPreferances = \DB::table('public_product_user_review')->where('current_status',2)->where('product_id',$productId)->where('header_id',$headerId)->where('select_type',5)->sum('leaf_id');
        $userCount = \DB::table('public_product_user_review')->where('current_status',2)->where('product_id',$productId)->where('header_id',$headerId)->count();
        $overallPreferanceUserCount = \DB::table('public_product_user_review')->where('current_status',2)->where('product_id',$productId)->where('header_id',$headerId)->where('select_type',5)->count();
        $question = \DB::table('public_review_questions')->where('header_id',$headerId)->where('questions->select_type',5)->first();
        $question = isset($question->questions) ? json_decode($question->questions) : null;
        $option = isset($question->option) ? $question->option : [];
        $meta = [];
        $meta['max_rating'] = count($option);
        $meta['overall_rating'] = $overallPreferanceUserCount > 0 ? $overallPreferances/$overallPreferanceUserCount : 0.00;
        $meta['count'] = $userCount;
        $meta['overall_preferance_user_count'] = $overallPreferanceUserCount;
        $meta['color_code'] = $this->getColorCode(floor($meta['overall_rating']));
        return $meta;
    }

    public function getFilterProfileIds($filters)
    {
        $profileIds = new Collection([]);

        return ['profile_id'=>$profileIds,'type'=>true];
    }

    public function comments(Request $request,$productId,$headerId,$questionId)
    {
        $product = PublicReviewProduct::where('id',$productId)->first();
        if($product == null)
        {
            return $this->sendError("Product is not available");
        }
        //paginate
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $this->model = Review::where('product_id',$productId)->where('question_id',$questionId)
            ->where('header_id',$headerId)->where('current_status',2)->skip($skip)->take($take)->get();

        return $this->sendResponse();
    }

    public function reportPdf(Request $request,$productId)
    {
        $product = PublicReviewProduct::where('id',$productId)->first();
        $globalQuestionId = $product->global_question_id;
        if ($product === null) {
            return $this->sendError("Invalid product.");
        }
        $profileId = $request->user()->profile->id;
        $headers = ReviewHeader::where('global_question_id',$globalQuestionId)->get();

        foreach ($headers as $header)
        {
            if($header->header_type == 'INSTRUCTIONS')
                continue;
            $headerId = $header->id;
            $withoutNest = \DB::table('public_review_questions')->where('global_question_id',$product->global_question_id)
                ->whereNull('parent_question_id')->where('header_id',$headerId)->where('is_active',1)->orderBy('id')->get();
            $withNested = \DB::table('public_review_questions')->where('global_question_id',$product->global_question_id)
                ->whereNotNull('parent_question_id')->where('is_active',1)->where('header_id',$headerId)->orderBy('id')->get();

            foreach ($withoutNest as &$data)
            {
                if(isset($data->questions)&&!is_null($data->questions))
                {
                    $data->questions = json_decode($data->questions);
                }
            }
            foreach ($withoutNest as &$data)
            {
                $i = 0;
                foreach ($withNested as $item)
                {
                    if($item->parent_question_id == $data->id)
                    {
                        $item->questions = json_decode($item->questions);
                        $item->questions->id = $item->id;
                        $item->questions->is_nested_question = $item->is_nested_question;
                        $item->questions->is_mandatory = $item->is_mandatory;
                        $item->questions->is_active = $item->is_active;
                        $item->questions->parent_question_id = $item->parent_question_id;
                        $item->questions->header_id = $item->header_id;
                        $item->questions->global_question_id = $item->global_question_id;
                        $data->questions->questions{$i} = $item->questions;
                        $i++;
                    }
                }
            }

            //filters data
            $filters = $request->input('filters');
            $resp = $this->getFilterProfileIds($filters);
            $profileIds = $resp['profile_id'];
            $type = $resp['type'];
            $boolean = 'and' ;
            $totalApplicants = \DB::table('public_product_user_review')->where('value','!=','')->where('current_status',2)->where('product_id',$productId)
                ->whereIn('profile_id', $profileIds, $boolean, $type)->distinct()->get(['profile_id'])->count();
            $model = [];
            foreach ($withoutNest as $data)
            {
                $reports = [];
                if(isset($data->questions)&&!is_null($data->questions))
                {
                    $reports['question_id'] = $data->id;
                    $reports['title'] = $data->title;
                    $reports['subtitle'] = $data->subtitle;
                    $reports['is_nested_question'] = $data->is_nested_question;
                    $reports['question'] = $data->questions ;
                    if($data->questions->is_nested_question == 1)
                    {
                        $subAnswers = [];
                        foreach ($data->questions->questions as $item)
                        {
                            $subReports = [];
                            $subReports['question_id'] = $item->id;
                            $subReports['title'] = $item->title;
                            $subReports['subtitle'] = isset($item->subtitle) ? $item->subtitle : null;
                            $subReports['is_nested_question'] = $item->is_nested_question;
                            $subReports['total_applicants'] = $totalApplicants;
                            $subReports['total_answers'] = \DB::table('public_product_user_review')->where('current_status',2)->where('product_id',$productId)
                                ->whereIn('profile_id', $profileIds, $boolean, $type)->where('question_id',$item->id)->distinct()->get(['profile_id'])->count();
                            $subReports['answer'] = \DB::table('public_product_user_review')->select('leaf_id','value','intensity',\DB::raw('count(*) as total'))->where('current_status',2)
                                ->whereIn('profile_id', $profileIds, $boolean, $type)->where('product_id',$productId)->where('question_id',$item->id)
                                ->orderBy('question_id','ASC')->orderBy('total','DESC')->groupBy('question_id','value','leaf_id','intensity')->get();
                            $subAnswers[] = $subReports;
                        }
                        $reports['nestedAnswers'] = $subAnswers;

                    }
                    else
                        unset($reports['nestedAnswers']);
                    $reports['total_applicants'] = $totalApplicants;
                    $reports['total_answers'] = \DB::table('public_product_user_review')->where('current_status',2)->where('product_id',$productId)
                        ->whereIn('profile_id', $profileIds, $boolean, $type)->where('question_id',$data->id)->distinct()->get(['profile_id'])->count();
                    if(isset($data->questions->select_type) && $data->questions->select_type == 3)
                    {
                        $reports['answer'] = Review::where('product_id',$productId)->where('question_id',$data->id)
                            ->whereIn('profile_id', $profileIds, $boolean, $type)->where('current_status',2)->where('header_id',$headerId)->skip(0)->take(3)->get();
                    }
                    else
                    {
                        $reports['answer'] = \DB::table('public_product_user_review')->select('leaf_id','value','intensity',\DB::raw('count(*) as total'))->where('current_status',2)
                            ->where('product_id',$productId)->where('question_id',$data->id)
                            ->whereIn('profile_id', $profileIds, $boolean, $type)->orderBy('question_id','ASC')->orderBy('total','DESC')->groupBy('question_id','value','leaf_id','intensity')->get();
                    }

                    if(isset($data->questions->is_nested_option))
                    {
                        $reports['is_nested_option'] = $data->questions->is_nested_option;
                        if($data->questions->is_nested_option == 1)
                        {
                            foreach($reports['answer'] as &$item)
                            {
                                $nestedOption = \DB::table('public_review_nested_options')->where('header_id',$headerId)
                                    ->where('question_id',$data->id)->where('id',$item->leaf_id)->where('value','like',$item->value)->first();
                                $item->path = isset($nestedOption->path) ? $nestedOption->path : null;
                            }
                        }
                    }

                    $model[] = $reports;
                }
            }
            $this->model[] = ['headerName'=>$header->header_type,'data'=>$model];
        }
        $data = $this->model;
        $pdf = PDF::loadView('collaborates.reports',['data' => $data,'filters'=>$filters]);
        $pdf = $pdf->output();
        $relativePath = "images/publicReview/$productId/product";
        $name = "product-".$productId.".pdf";
        file_put_contents($name,$pdf);
        $s3 = \Storage::disk('s3');
        $resp = $s3->putFile($relativePath, new File($name), ['visibility'=>'public']);
        $this->model = \Storage::url($resp);
        return $this->sendResponse();

    }

    public function getAnswer(Request $request,$productId,$headerId,$questionId,$optionId)
    {
        $option = $request->input('q');
        $this->model = \DB::table('public_product_user_review')->select('intensity',\DB::raw('count(*) as total'))->where('current_status',2)
            ->where('product_id',$productId)->where('question_id',$questionId)->where('leaf_id',$optionId)->where('value','like',$option)
            ->orderBy('total','DESC')->groupBy('intensity')->get();
        return $this->sendResponse();
    }

    private function checkValue($a,$b)
    {
        if($a == $b || $a == " ".$b || " ".$a == $b)
            return 1;
        return 0;
    }
}
