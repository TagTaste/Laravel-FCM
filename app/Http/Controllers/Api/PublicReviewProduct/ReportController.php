<?php

namespace App\Http\Controllers\APi\PublicReviewProduct;

use App\PublicReviewPorduct;
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

    public function reportSummary(Request $request,$productId)
    {
        $product = PublicReviewPorduct::where('id',$productId)->first();
        if($product == null)
        {
            return $this->sendError("Product is not available");
        }
        $this->model = [];
        $this->model['title'] = 'Rating';
        $this->model['description'] = 'Following graph shows the overall preference of tasters for this product based on Appearance, Aroma, Aromatics, Taste, and Texture on an 8-point scale.';
        $this->model['info'] = ['text'=>'this is text','link'=>null,'images'=>[]];
        $this->model['chat_header'] = 'Product Experience';
        $this->model['header_rating'] = $this->getHeaderRating($product);
        $this->model['self_review'] = $this->getSelfReview($product,$request->user()->profile->id);
        return $this->sendResponse();
    }

    public function getSelfReview($product,$loggedInProfileId)
    {
        $productId = $product->id;
        $review = \DB::table('public_product_user_review')->where('product_id',$productId)->where('profile_id',$loggedInProfileId)
            ->where('select_type',3)->first();
        return isset($review->value) ?  $review->value : null;
    }

    public function getHeaderRating($product)
    {
        $globalQuestionId = $product->global_question_id;
        $headers = ReviewHeader::where('global_question_id',$globalQuestionId)->get();
        $productId = $product->id;
        $overallPreferances = \DB::table('public_product_user_review')->where('product_id',$productId)->where('select_type',5)->get();

        $headerRating = [];
        foreach ($headers as $header)
        {
            $userCount = 0;
            $headerRatingSum = 0;
            foreach ($overallPreferances as $overallPreferance)
            {
                if($overallPreferance->header_id == $header->id)
                {
                    $headerRatingSum += $overallPreferance->value;
                    $userCount++;
                }
            }
            $headerRating[] = ['header_type'=>$header->header_type,'meta'=>$this->getRatingMeta($userCount,$headerRatingSum)];
        }

        return $headerRating;

    }

    protected function getRatingMeta($userCount,$headerRatingSum)
    {
        $meta = [];
        $meta['max_rating'] = 8;
        $meta['overall_rating'] = $userCount > 0 ? $headerRatingSum/$userCount : 0.00;
        $meta['count'] = $userCount;
        $meta['color_code'] = $this->getColorCode($meta['overall_rating']);
        return $meta;
    }

    protected function getColorCode($value)
    {
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
        $product = PublicReviewPorduct::where('id',$productId)->first();

        if ($product === null) {
            return $this->sendError("Invalid product.");
        }
        $profileId = $request->user()->profile->id;

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
                    $item->questions->collaborate_id = $item->collaborate_id;
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
        $totalApplicants = \DB::table('public_product_user_review')->where('value','!=','')->where('current_status',3)->where('product_id',$productId)
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
                        $subReports['total_answers'] = \DB::table('public_product_user_review')->where('current_status',3)->where('product_id',$productId)
                            ->whereIn('profile_id', $profileIds, $boolean, $type)->where('question_id',$item->id)->distinct()->get(['profile_id'])->count();
                        $subReports['answer'] = \DB::table('public_product_user_review')->select('leaf_id','value','intensity',\DB::raw('count(*) as total'))->where('current_status',3)
                            ->whereIn('profile_id', $profileIds, $boolean, $type)->where('product_id',$productId)->where('question_id',$item->id)
                            ->orderBy('question_id','ASC')->orderBy('total','DESC')->groupBy('question_id','value','leaf_id','intensity')->get();
                        $subAnswers[] = $subReports;
                    }
                    $reports['nestedAnswers'] = $subAnswers;

                }
                else
                    unset($reports['nestedAnswers']);
                $reports['total_applicants'] = $totalApplicants;
                $reports['total_answers'] = \DB::table('public_product_user_review')->where('current_status',3)->where('product_id',$productId)
                    ->whereIn('profile_id', $profileIds, $boolean, $type)->where('question_id',$data->id)->distinct()->get(['profile_id'])->count();
                if(isset($data->questions->select_type) && $data->questions->select_type == 3)
                {
                    $reports['answer'] = Review::where('product_id',$productId)->where('question_id',$data->id)
                        ->whereIn('profile_id', $profileIds, $boolean, $type)->where('current_status',3)->where('header_id',$headerId)->skip(0)->take(3)->get();
                }
                else
                {
                    $reports['answer'] = \DB::table('public_product_user_review')->select('leaf_id','value','intensity',\DB::raw('count(*) as total'))->where('current_status',3)
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
        $this->model = $model;

        return $this->sendResponse();
    }

    public function getFilterProfileIds($filters)
    {
        $profileIds = new Collection([]);

        return ['profile_id'=>$profileIds,'type'=>true];
    }

    public function comments(Request $request,$productId,$headerId,$questionId)
    {
        $product = PublicReviewPorduct::where('id',$productId)->first();
        if($product == null)
        {
            return $this->sendError("Product is not available");
        }
        //paginate
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $this->model = Review::where('product_id',$productId)->where('question_id',$questionId)
            ->where('header_id',$headerId)->where('current_status',3)->skip($skip)->take($take)->get();

        return $this->sendResponse();
    }

    public function reportPdf(Request $request,$productId)
    {
        $product = PublicReviewPorduct::where('id',$productId)->first();
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
                        $item->questions->collaborate_id = $item->collaborate_id;
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
            $totalApplicants = \DB::table('public_product_user_review')->where('value','!=','')->where('current_status',3)->where('product_id',$productId)
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
                            $subReports['total_answers'] = \DB::table('public_product_user_review')->where('current_status',3)->where('product_id',$productId)
                                ->whereIn('profile_id', $profileIds, $boolean, $type)->where('question_id',$item->id)->distinct()->get(['profile_id'])->count();
                            $subReports['answer'] = \DB::table('public_product_user_review')->select('leaf_id','value','intensity',\DB::raw('count(*) as total'))->where('current_status',3)
                                ->whereIn('profile_id', $profileIds, $boolean, $type)->where('product_id',$productId)->where('question_id',$item->id)
                                ->orderBy('question_id','ASC')->orderBy('total','DESC')->groupBy('question_id','value','leaf_id','intensity')->get();
                            $subAnswers[] = $subReports;
                        }
                        $reports['nestedAnswers'] = $subAnswers;

                    }
                    else
                        unset($reports['nestedAnswers']);
                    $reports['total_applicants'] = $totalApplicants;
                    $reports['total_answers'] = \DB::table('public_product_user_review')->where('current_status',3)->where('product_id',$productId)
                        ->whereIn('profile_id', $profileIds, $boolean, $type)->where('question_id',$data->id)->distinct()->get(['profile_id'])->count();
                    if(isset($data->questions->select_type) && $data->questions->select_type == 3)
                    {
                        $reports['answer'] = Review::where('product_id',$productId)->where('question_id',$data->id)
                            ->whereIn('profile_id', $profileIds, $boolean, $type)->where('current_status',3)->where('header_id',$headerId)->skip(0)->take(3)->get();
                    }
                    else
                    {
                        $reports['answer'] = \DB::table('public_product_user_review')->select('leaf_id','value','intensity',\DB::raw('count(*) as total'))->where('current_status',3)
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


}
