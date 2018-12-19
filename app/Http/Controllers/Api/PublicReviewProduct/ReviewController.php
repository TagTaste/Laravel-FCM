<?php

namespace App\Http\Controllers\APi\PublicReviewProduct;

use App\Comment;
use App\PublicReviewPorduct;
use App\PublicReviewProduct\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class ReviewController extends Controller
{
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
    public function __construct(Review $model)
    {
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
        $loggedInPorfileId = $request->user()->profile->id;
        //paginate
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);

        $this->model = $this->model->where('product_id',$productId)->where('profile_id',$loggedInPorfileId)->where('select_type',3)
            ->where('key','like','comment')->skip($skip)->take($take)->get();

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
    public function show($id)
    {
        //
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

    public function productReview(Request $request,$productId,$headerId)
    {
        $this->now = Carbon::now()->toDateTimeString();
        $loggedInProfileId = $request->user()->profile->id;
        $data = [];
        $answers = $request->input('answer');

        $checkProduct = \DB::table('public_review_products')->where('id',$productId)->exists();

        if(!$checkProduct)
        {
            return $this->sendError("Wrong product reviewing");
        }
        Review::where('profile_id',$loggedInProfileId)->where('product_id',$productId)
            ->where('header_id',$headerId)->delete();

        if(count($answers))
        {
            foreach ($answers as $answer)
            {
                $options = isset($answer['option']) ? $answer['option'] : [];
                $questionId = $answer['question_id'];
                foreach ($options as $option)
                {
                    $leafId = isset($option['id']) && $option['id'] != 0 ? $option['id'] : null;
                    $valueId = isset($option['value_id']) && $option['value_id'] != 0 ? $option['id'] : null;
                    $intensity = isset($option['intensity']) && !is_null($option['intensity']) && !empty($option['intensity']) ? $option['intensity'] : null;
                    $data[] = ['key'=>null,'value'=>$option['value'],'leaf_id'=>$leafId,
                        'question_id'=>$questionId,'header_id'=>$headerId,
                        'profile_id'=>$loggedInProfileId,
                        'product_id'=>$productId,'intensity'=>$intensity,'current_status'=>1,'value_id'=>$valueId,
                        'created_at'=>$this->now,'updated_at'=>$this->now];
                }
                if(isset($answer['comment']) && !is_null($answer['comment']) && !empty($answer['comment']))
                {
                    $data[] = ['key'=>"comment",'value'=>$answer['comment'],'leaf_id'=>0,
                        'question_id'=>$questionId,'header_id'=>$headerId,
                        'profile_id'=>$loggedInProfileId,
                        'product_id'=>$productId,'intensity'=>null,'current_status'=>1,'value_id'=>null,
                        'created_at'=>$this->now,'updated_at'=>$this->now];
                }
            }
        }
        if(count($data)>0)
        {
            $this->model = $this->model->create($data);
        }
        return $this->sendResponse();
    }

    public function comments(Request $request,$productId,$reviewId)
    {
        $model = $this->model->where('id',$reviewId)->where('product_id',$productId)->first();
        if($model == null)
        {
            $this->model = [];
            return $this->sendError("review is not available");
        }
        $page = $request->input('page') ? intval($request->input('page')) : 1;
        $page = $page == 0 ? 1 : $page;
        $this->model = [];
        $this->model['data'] = $model->comments()->orderBy('created_at','desc')->skip(($page - 1) * 10)->take(10)->get();
        $this->model['next_page'] = $page > 1 ? $page - 1 : null;
        $this->model['count'] = $model->comments()->count();
        $this->model['previous_page'] = count($this->model['data']) >= 10 && $page*10 < $this->model['count']  ? $page + 1 : null;

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

        $comment->content = $request->input("content");
        $comment->user_id = $request->user()->id;
        $comment->save();

        $review->comments()->attach($comment->id);

        $this->model = $comment;
        return $this->sendResponse();
    }

    public function reviewAnswers(Request $request, $productId, $headerId)
    {
        $this->now = Carbon::now()->toDateTimeString();
        $data = [];
        $answers = $request->input('answer');
        $loggedInProfileId = $request->user()->profile->id ;
        $product = PublicReviewPorduct::where('id',$productId)->first();
        if($product === null){
            return $this->sendError("Product not found.");
        }
        $userReview = Review::where('profile_id',$loggedInProfileId)->where('product_id',$productId)->orderBy('id','desc')->first();
        if(isset($userReview) &&$userReview->current_status == 1)
        {
            return $this->sendError("User already reviewd.");
        }
        $currentStatus = $request->has('current_status') ? $request->input('current_status') : 0;

        $this->model = Review::where('profile_id',$loggedInProfileId)->where('product_id',$productId)
            ->where('header_id',$headerId)->delete();

        if(count($answers))
        {
            foreach ($answers as $answer)
            {
                $options = isset($answer['option']) ? $answer['option'] : [];
                $questionId = $answer['question_id'];
                foreach ($options as $option)
                {
                    $leafId = isset($option['id']) && $option['id'] != 0 ? $option['id'] : null;
                    $valueId = isset($option['value_id']) && $option['value_id'] != 0 ? $option['id'] : null;
                    $intensity = isset($option['intensity']) && !is_null($option['intensity']) && !empty($option['intensity']) ? $option['intensity'] : null;
                    $selectType = isset($option['select_type']) && is_null($option['select_type']) ? $option['select_type'] : null;
                    $data[] = ['key'=>null,'value'=>$option['value'],'leaf_id'=>$leafId,
                        'question_id'=>$questionId,'header_id'=>$headerId,
                        'profile_id'=>$loggedInProfileId, 'product_id'=>$productId,'intensity'=>$intensity,
                        'current_status'=>$currentStatus,'value_id'=>$valueId,
                        'created_at'=>$this->now,'updated_at'=>$this->now,'select_type'=>$selectType];
                }
                if(isset($answer['comment']) && !is_null($answer['comment']) && !empty($answer['comment']))
                {
                    $data[] = ['key'=>"comment",'value'=>$answer['comment'],'leaf_id'=>0,
                        'question_id'=>$questionId,'header_id'=>$headerId,
                        'profile_id'=>$loggedInProfileId, 'product_id'=>$productId,'intensity'=>null,
                        'current_status'=>$currentStatus,'value_id'=>null,
                        'created_at'=>$this->now,'updated_at'=>$this->now,'select_type'=>$selectType];
                }
            }
        }
        if(count($data)>0)
        {
            $this->model = Review::insert($data);
            if($currentStatus == 1)
            {
                Review::where('profile_id',$loggedInProfileId)->where('product_id',$productId)
                    ->where('header_id',$headerId)->update(['current_status'=>$currentStatus]);
            }
        }
        return $this->sendResponse();
    }

}
