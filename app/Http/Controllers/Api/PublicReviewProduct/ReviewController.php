<?php

namespace App\Http\Controllers\Api\PublicReviewProduct;

use App\Comment;
use App\PublicReviewProduct;
use App\PublicReviewProduct\Review;
use App\PublicReviewProduct\ReviewHeader;
use Carbon\Carbon;
use App\Traits\CheckTags;
use App\Events\Actions\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

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
    public function index(Request $request,$productId)
    {
        $product = PublicReviewProduct::where('id',$productId)->first();
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
            $header = ReviewHeader::where('global_question_id',$product->global_question_id)->where('header_selection_type',2)->first();
            $this->model = $this->model->where('product_id',$productId)->where('header_id',$header->id)
                ->where('select_type',5);

            if($request->has('rating_low'))
                $this->model = $this->model->orderBy('leaf_id', 'ASC')->skip($skip)->take($take)->get();
            else if($request->has('rating_high'))
                $this->model = $this->model->orderBy('leaf_id', 'DESC')->skip($skip)->take($take)->get();
            else
                $this->model = $this->model->orderBy('updated_at',$sortBy)->skip($skip)->take($take)->get();

            return $this->sendResponse();
        } else {
            $header = ReviewHeader::where('global_question_id',$product->global_question_id)->where('header_selection_type',2)->first();
            $this->model = $this->model->where('product_id',$productId)->where('header_id',$header->id)
                ->where('select_type',5);

            if($request->has('rating_low'))
                $this->model = $this->model->orderBy('leaf_id', 'ASC')->get();
            else if($request->has('rating_high'))
                $this->model = $this->model->orderBy('leaf_id', 'DESC')->get();
            else
                $this->model = $this->model->orderBy('updated_at',$sortBy)->get();

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
    public function foodShot(Request $request,$productId)
    {

        $this->validateProduct($productId);
        //order by
        $sortBy = $request->has('sort_by') ? $request->input('sort_by') : 'DESC';
        $sortBy = $sortBy == 'DESC' ? 'DESC' : 'ASC';

        $food_shots = Review::where('public_product_user_review.product_id',$productId)
            ->join('public_review_products as prod','prod.id','=','public_product_user_review.product_id')
            ->join('public_review_question_headers as headers',function($join){
                $join->on('headers.global_question_id','=','prod.global_question_id');
                $join->where('headers.header_selection_type',2);
            })
            ->join('public_product_user_review as r1',function($join){
                $join->on('r1.profile_id','=','public_product_user_review.profile_id');
                $join->on('r1.header_id','=','headers.id');
                $join->where('r1.select_type',5);
            })
            ->where('public_product_user_review.select_type',6)
            ->whereNotNull('public_product_user_review.meta')
            ->orderBy('public_product_user_review.updated_at',$sortBy)
            ->where('public_product_user_review.current_status',2)
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
        $product = PublicReviewProduct::where('id',$id)->first();
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
    public function store(Request $request,$productId,$headerId)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($productId,$reviewId)
    {
        $product = PublicReviewProduct::where('id',$productId)->first();
        if($product == null)
        {
            return $this->sendError("Product is not available");
        }
        $header = ReviewHeader::where('global_question_id',$product->global_question_id)->where('header_selection_type',2)->first();
        $this->model = $this->model->where('product_id',$productId)->where('id',$reviewId)->where('header_id',$header->id)
            ->where('select_type',5)->first();

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

    public function comments(Request $request,$productId,$reviewId)
    {
        $model = $this->model->where('id',$reviewId)->where('product_id',$productId)->first();
        if ($model == null) {
            $this->model = [];
            return $this->sendError("review is not available");
        }
        $page = $request->input('page') ? intval($request->input('page')) : 1;
        $page = $page == 0 ? 1 : $page;
        $this->model = [];
        $this->model = $model->toArray();
        $this->model['comments'] = $model->comments()->orderBy('created_at','desc')->skip(($page - 1) * 10)->take(10)->get();
        $this->model['next_page'] = $page > 1 ? $page - 1 : null;
        $this->model['count'] = $model->comments()->count();
        $this->model['previous_page'] = count($this->model['comments']) >= 10 && $page*10 < $this->model['count']  ? $page + 1 : null;

        return $this->sendResponse();
    }

    public function commentsPost(Request $request,$productId,$reviewId)
    {
        $review = $this->model->where('id',$reviewId)->where('product_id',$productId)->first();
        if($review == null)
        {
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
        if($comment->has_tags){
            event(new Tag($review,$request->user()->profile,$comment->content, null, null, null, $comment));
        }
        else
        {
            event(new \App\Events\Actions\Comment($review,$request->user()->profile, $comment->content, null, null, null, $comment));
        }

        $this->model = $comment;

        return $this->sendResponse();
    }

    public function commentsDelete(Request $request, $productId,$reviewId,$commentId){
        $review = $this->model->where('id',$reviewId)->where('product_id',$productId)->first();
        if($review == null)
        {
            $this->model = [];

            return $this->sendError("review is not available");
        }
        $this->now = Carbon::now()->toDateTimeString();
        $this->model = $review->comments()->where('id',$commentId)->update(['deleted_at'=>$this->now]);
        return $this->sendResponse();

    }

    public function reviewAnswers(Request $request, $productId, $headerId)
    {
        $this->now = Carbon::now()->toDateTimeString();
        $data = [];
        $answers = $request->input('answer');
        $loggedInProfileId = $request->user()->profile->id ;
        $product = PublicReviewProduct::where('id',$productId)->first();
        if($product === null){
            return $this->sendError("Product not found.");
        }
        $userReview = Review::where('profile_id',$loggedInProfileId)->where('product_id',$productId)->orderBy('id','desc')->first();
        if(isset($userReview) && $userReview->current_status == 2)
        {
            return $this->sendError("User already reviewd.");
        }
        $currentStatus = $request->has('current_status') ? $request->input('current_status') : 1;

        $this->model = Review::where('profile_id',$loggedInProfileId)->where('product_id',$productId)
            ->where('header_id',$headerId)->delete();

        if(count($answers))
        {
            foreach ($answers as $answer)
            {
                $options = isset($answer['option']) ? $answer['option'] : [];
                $questionId = $answer['question_id'];
                $selectType = isset($answer['select_type']) && !is_null($answer['select_type']) ? $answer['select_type'] : null;
                if(isset($answer['option'])) {
                    $optionVals = \DB::table('public_review_questions')->where('id',$questionId)->get();
                    $optionVals = json_decode($optionVals[0]->questions);
                    if(isset($optionVals->nested_option_list)) {
                        $optionVals = $optionVals->nested_option_list;
                    } else if (isset($optionVals->option)) {
                        $optionVals = $optionVals->option;
                    } else {
                        $optionVals = null;
                    }
                }
                foreach ($options as $option)
                {
                    $leafId = isset($option['id']) && $option['id'] != 0 ? $option['id'] : null;
                    if($leafId == null)
                    {
                        $this->model = false;
                        return $this->sendError("Leaf id can not null");
                    }
                    if($optionVals == "AROMA") {
                        $option_type = \DB::table('public_review_nested_options')->where('id',$leafId)->select('option_type')->first()->option_type;
                    } else {
                        $option_type = isset($optionVals[$leafId-1]->option_type) ? $optionVals[$leafId-1]->option_type : 0;
                    }
                    $valueId = isset($option['value_id']) && $option['value_id'] != 0 ? $option['id'] : null;
                    $intensity = isset($option['intensity']) && !is_null($option['intensity']) && !empty($option['intensity']) ? $option['intensity'] : null;
                    $data[] = ['key'=>null,'value'=>$option['value'],'leaf_id'=>$leafId,
                        'question_id'=>$questionId,'header_id'=>$headerId,
                        'profile_id'=>$loggedInProfileId, 'product_id'=>$productId,'intensity'=>$intensity,
                        'current_status'=>$currentStatus,'value_id'=>$valueId,
                        'created_at'=>$this->now,'updated_at'=>$this->now,'select_type'=>$selectType,'meta'=>null,'option_type'=>$option_type];
                }
                if(isset($answer['meta']) && !is_null($answer['meta']) && !empty($answer['meta']))
                {
                    $data[] = ['key'=>"authenticity_check",'value'=>"meta",'leaf_id'=>0,
                        'question_id'=>$questionId,'header_id'=>$headerId,
                        'profile_id'=>$loggedInProfileId, 'product_id'=>$productId,'intensity'=>null,
                        'current_status'=>$currentStatus,'value_id'=>null,
                        'created_at'=>$this->now,'updated_at'=>$this->now,'select_type'=>6,'meta'=>$answer['meta'],'option_type'=>0];
                }
                if(isset($answer['comment']) && !is_null($answer['comment']) && !empty($answer['comment']))
                {

                    $data[] = ['key'=>"comment",'value'=>$answer['comment'],'leaf_id'=>0,
                        'question_id'=>$questionId,'header_id'=>$headerId,
                        'profile_id'=>$loggedInProfileId, 'product_id'=>$productId,'intensity'=>null,
                        'current_status'=>$currentStatus,'value_id'=>null,
                        'created_at'=>$this->now,'updated_at'=>$this->now,'select_type'=>$selectType,'meta'=>null,'option_type'=>0];
                }
            }
        }
        if(count($data)>0)
        {
            $this->model = Review::insert($data);
            if($currentStatus == 2)
            {
                $mandatoryQuestion = \DB::table('public_review_questions')->where('global_question_id',$product->global_question_id)->where('is_mandatory',1)->get();
                $mandatoryQuestionsId = $mandatoryQuestion->pluck('id');
                $mandatoryReviewCount = \DB::table('public_product_user_review')->where('product_id',$productId)->whereIn('question_id',$mandatoryQuestionsId)->where('profile_id',$loggedInProfileId)->distinct('question_id')->count('question_id');
                if($mandatoryQuestion->count() == $mandatoryReviewCount)
                {
                    $this->model = true;
                    \DB::table('public_review_user_timings')->where('profile_id',$loggedInProfileId)->where('product_id',$productId)->update(['updated_at'=>$this->now]);
                    Review::where('profile_id',$loggedInProfileId)->where('product_id',$productId)
                        ->update(['current_status'=>$currentStatus]);
                }
                else
                {
                    $this->model = false;
                }
            }
        }
        return $this->sendResponse();
    }

    public function uploadImage(Request $request, $productId)
    {
        $profileId = $request->user()->profile->id;
        $imageName = str_random("32") . ".jpg";
        $path = "images/public-review/products/$productId/profile/$profileId";
        $randnum = rand(10,1000);
        //create a tiny image
        $path = $path."/brand_logo/$randnum";
        $thumbnail = \Image::make($request->file('image'))->resize(320, null,function ($constraint) {
            $constraint->aspectRatio();
        })->blur(1)->stream('jpg',70);
        \Storage::disk('s3')->put($path, (string) $thumbnail,['visibility'=>'public']);
        $response = \Storage::url($path);
        if(!$response){
            throw new \Exception("Could not save image " . $imageName . " at " . $path);
        }
        $this->model = $response;
        return $this->sendResponse();
    }

}
