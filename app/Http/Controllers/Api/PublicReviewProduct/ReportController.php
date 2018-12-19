<?php

namespace App\Http\Controllers\APi\PublicReviewProduct;

use App\Comment;
use App\PublicReviewPorduct;
use App\PublicReviewProduct\Review;
use App\PublicReviewProduct\ReviewHeader;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class ReviewController extends Controller
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

        return $this->sendResponse();
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

}
