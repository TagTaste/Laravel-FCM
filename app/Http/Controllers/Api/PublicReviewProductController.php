<?php

namespace App\Http\Controllers\APi;

use App\PublicReviewPorduct;
use App\PublicReviewProduct\ProductCategory;
use App\Recipe\Collaborate;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class PublicReviewProductController extends Controller
{
    /**
     * Variable to model
     *
     * @var PublicReviewPorduct
     */
    protected $model;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(PublicReviewPorduct $model)
    {
        $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //paginate
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);

        $this->model = $this->model->where('is_active',1)->skip($skip)->take($take)->get();

        return $this->sendResponse();

    }

    /**
     * Show the form for creating a new resource.0
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        if(isset($inputs['images_meta']))
        {
            $images = $request->input('images_meta');
            $imageArray = [];
            foreach ($images as $image)
                $imageArray[] = $image;
            $inputs['images_meta'] = json_encode($imageArray,true);
        }
        $this->model = $this->model->create($inputs);
        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $this->model = $this->model->where('is_active',1)->where('id',$id)->first();

        return $this->sendResponse();

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
        $inputs = $request->all();
        $this->model = $this->model->where('id',$id)->update($inputs);
        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $this->model = $this->model->where('id',$id)->delete();
        return $this->sendResponse();
    }

    public function checkUuId($uuId)
    {
        $check = PublicReviewPorduct::where('id',$uuId)->exists();
        if($check)
        {
            $uuId = str_random("32");
            $this->checkUuId($uuId);
        }
        return $uuId;
    }

    public function discover(Request $request)
    {
        $this->model = [];
        $recommended = PublicReviewPorduct::where('mark_featured',1)->inRandomOrder()->limit(20)->get();
        if($recommended->count())
            $this->model[] = ['title'=>'Review and Earn TT Currency','subtitle'=>'100 POINTS ON EVERY REVIEW','item'=>$recommended,
                'ui_type'=>0,'color_code'=>'rgb(255, 255, 255)'];

        $categories = ProductCategory::where('is_active')->inRandomOrder()->limit(20)->get();
        if($categories->count())
            $this->model[] = ['title'=>'Categories','subtitle'=>'LENSES FOR THE F&B INDUSTRY','item'=>$categories,
                'ui_type'=>0,'color_code'=>'rgb(255, 255, 255)'];

//        $categories = ProductCategory::where('is_active')->get();
//        $this->model[] = ['title'=>'Based on your Interest','subtitle'=>'DARK CHOCOLATE, WINE AND 2 OTHERS','item'=>$categories,
//            'ui_type'=>0,'color_code'=>'rgb(255, 255, 255)'];

        $collaborates = Collaborate::where('state',1)->where('collaborate_type','like','product-review')->inRandomOrder()->limit(5)->get();
        if($collaborates->count())
            $this->model[] = ['title'=>'Private Reviews','subtitle'=>'COLLABORATION BY F&B BRANDS','item'=>$collaborates,
                'ui_type'=>1,'color_code'=>'rgb(255, 255, 255)'];

        $recently = PublicReviewPorduct::where('mark_featured',1)->orderBy('updated_at','desc')->limit(20)->get();
        if($recently->count())
            $this->model[] = ['title'=>'Newly Added Products','subtitle'=>'BE THE FIRST ONE TO REVIEW','item'=>$recently,
                'ui_type'=>0,'color_code'=>'rgb(255, 255, 255)'];

        return $this->sendResponse();
    }

    public function categoryProducts(Request $request, $categoryId)
    {
        //paginate
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);

        $this->model = $this->model->where('product_category_id',$categoryId)->where('is_active',1)->skip($skip)->take($take)->get();

        return $this->sendResponse();
    }
}
