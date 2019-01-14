<?php

namespace App\Http\Controllers\Api;

use App\PublicReviewProduct;
use App\PublicReviewProduct\ProductCategory;
use App\Recipe\Collaborate;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Webpatser\Uuid\Uuid;
use App\SearchClient;

class PublicReviewProductController extends Controller
{
    /**
     * Variable to model
     *
     * @var PublicReviewProduct
     */
    protected $model;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(PublicReviewProduct $model)
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

        $type = 'product';
        $query = $request->input('q');
        $profileId = $request->user()->profile->id;
        if(isset($query) && !is_null($query) && !empty($query))
        {
            return $this->getSearchData($request,$query,$type);
        }
        $filters = $request->input('filters');
        if(!empty($filters))
        {
            $productIds =  \App\Filter\PublicReviewProduct::getModelIds($filters,$skip,$take);

            $this->model = $this->model->whereIn('id',$productIds)->where('is_active',1)->get();

            return $this->sendResponse();
        }

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
        $inputs['id'] = (string) Uuid::generate(4);
        if(isset($inputs['images_meta']))
        {
            $images = $request->input('images_meta');
            $imageArray = [];
            foreach ($images as $image)
                $imageArray[] = $image;
            $inputs['images_meta'] = json_encode($imageArray,true);
        }
        $this->model = $this->model->create($inputs);
        \App\Filter\PublicReviewProduct::addModel($this->model);
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
        $this->model = \App\PublicReviewProduct::where('id',$id)->first();
        \App\Filter\PublicReviewProduct::addModel($this->model);
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
        $check = PublicReviewProduct::where('id',$uuId)->exists();
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
        $recommended = PublicReviewProduct::where('mark_featured',1)->inRandomOrder()->limit(20)->get();
        if($recommended->count())
            $this->model[] = ['title'=>'Featured Products','subtitle'=>'Products in focus this week','item'=>$recommended,
                'ui_type'=>3,'color_code'=>'rgb(255, 255, 255)','type'=>'product','is_see_more'=>1];

        //        $categories = ProductCategory::where('is_active')->get();
        $this->model[] = ['title'=>'Based on your Interest','subtitle'=>'DARK CHOCOLATE, WINE AND 2 OTHERS','item'=>$recommended,
            'ui_type'=>0,'color_code'=>'rgb(255, 255, 255)','type'=>'product','is_see_more'=>1];

        $categories = ProductCategory::where('is_active',1)->inRandomOrder()->limit(20)->get();
        if($categories->count())
            $this->model[] = ['title'=>'Categories','subtitle'=>'LENSES FOR THE F&B INDUSTRY','item'=>$categories,
                'ui_type'=>0,'color_code'=>'rgb(255, 255, 255)','type'=>'category','is_see_more'=>1];

        $recently = PublicReviewProduct::where('mark_featured',1)->orderBy('updated_at','desc')->limit(20)->get();
        if($recently->count())
            $this->model[] = ['title'=>'Newly Added Products','subtitle'=>'BE THE FIRST ONE TO REVIEW','item'=>$recently,
                'ui_type'=>2,'color_code'=>'rgb(255, 255, 255)','type'=>'product','is_see_more'=>1];

        $collaborates = Collaborate::where('state',1)->where('collaborate_type','like','product-review')->inRandomOrder()->limit(5)->get();
        if($collaborates->count())
            $this->model[] = ['title'=>'Private Reviews','subtitle'=>'COLLABORATION BY F&B BRANDS','item'=>$collaborates,
                'ui_type'=>2,'color_code'=>'rgb(255, 255, 255)','type'=>'collaborate','is_see_more'=>1];

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

    public function getFilters(Request $request)
    {
        $this->model = \App\Filter::getFilters("publicReviewProduct");
        return $this->sendResponse();
    }

    /**
     * @param $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function similarProducts($productId)
    {
        $product = $this->model->where('id', $productId)->first();
        if($product == null)
        {
            return $this->sendError("Invalid Product Id");
        }
        $filter['Sub Category'][] = $product['product_sub_category']['name'];
        $filter['Category'][] = $product['product_category']['name'];
        $filter['By Brand'][] = $product['brand_name'];
        $filter['By Company'][] = $product['company_name'];
        $similar= \App\Filter\PublicReviewProduct::getModelIds($filter)->toArray ();
        $this->model = \App\PublicReviewProduct::whereIn('id',$similar)->where('id','!=',$productId)->skip(0)->take(10)->get();
        return $this->sendResponse();
    }

    public function getSearchData($request,$query,$type)
    {
        $params = [
            'index' => "api",
            'body' => [
                'query' => [
                    'query_string' => [
                        'query' => $query
                    ]
                ]
            ]
        ];

        if($type){
            $params['type'] = $type;
        }
        $client = SearchClient::get();

        $response = $client->search($params);
        $this->model = [];

        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);

        if($response['hits']['total'] > 0){
            $hits = collect($response['hits']['hits']);
            $hits = $hits->groupBy("_type");

            foreach($hits as $name => $hit){
                $this->model[$name] = [];
                $ids = $hit->pluck('_id')->toArray();
                $searched = $this->getModels($name,$ids,$request->input('filters'),$skip,$take);

                $suggestions = $this->filterSuggestions($query,$name,$skip,$take);
                $suggested = collect([]);
                if(!empty($suggestions)){
                    $suggested = $this->getModels($name,array_pluck($suggestions,'id'));
                }
                if($suggested->count() > 0)
                    $this->model[$name] = $searched->merge($suggested)->sortBy('name');
                else
                    $this->model[$name] = $searched;
            }
            $productData = [];
            if(isset($this->model['product']))
            {
                $products = $this->model['product'];
                foreach ($products as &$product)
                {
                    $product->overall_rating = $product->getOverallRatingAttribute();
                    $productData[] = $product;
                }
            }
            $this->model = [];
            $this->model = $productData;
            return $this->sendResponse();

        }

        $suggestions = $this->filterSuggestions($query,$type,$skip,$take);
        $suggestions = $this->getModels($type,array_pluck($suggestions,'id'));

        if($suggestions && $suggestions->count()){
//            if(!array_key_exists($type,$this->model)){
//                $this->model[$type] = [];
//            }
            $this->model[$type] = $suggestions->toArray();
        }

        if(!empty($this->model)){
            $productData = [];
            if(isset($this->model['product']))
            {
                $products = $this->model['product'];
                foreach ($products as &$product)
                {
                    $product->overall_rating = $product->getOverallRatingAttribute();
                    $productData[] = $product;
                }
            }
            $this->model = [];
            $this->model = $productData;
            return $this->sendResponse();
        }
        $this->model = [];
        $this->messages = ['Nothing found.'];
        return $this->sendResponse();
    }

    private function getModels($type, $ids = [], $filters = [],$skip = null ,$take = null)
    {
        if(empty($ids)){
            return false;
        }
        $model = new PublicReviewProduct();

        if(!empty($filters) && isset($this->filters[$type])){
            $modelIds = \App\Filter\PublicReviewProduct::getModelIds($filters,$skip,$take);
            if($modelIds->count()){
                $ids = array_merge($ids,$modelIds->toArray());
            }
            return $model::whereIn('id',$ids)->whereNull('deleted_at')->get();

        }
        $model = $model::whereIn('id',$ids)->whereNull('deleted_at');

        if(null !== $skip && null !== $take){
            $model = $model->skip($skip)->take($take);
        }

        return $model->get();


    }

    private function filterSuggestions(&$term,$type = null,$skip,$take)
    {

        $suggestions = [];
        $products = \DB::table('products')->where('name', 'like','%'.$term.'%')->whereNull('deleted_at')->orderBy('name','asc')->skip($skip)
            ->take($take)->get();

        if(count($products)){
            foreach($products as $product){
                $product->type = "product";
                $suggestions[] = (array) $product;
            }
        }
        return $suggestions;
    }

    public function uploadImageProduct(Request $request)
    {
        $profileId = $request->user()->profile->id;
        $imageName = str_random("32") . ".jpg";
        $path = "images/p/$profileId/collaborate";
        $randnum = rand(10,1000);
        $response['original_photo'] = \Storage::url($request->file('image')->storeAs($path."/original/$randnum",$imageName,['visibility'=>'public']));
        //create a tiny image
        $path = $path."/tiny/$randnum" . str_random(20) . ".jpg";
        $thumbnail = \Image::make($request->file('image'))->resize(50, null,function ($constraint) {
            $constraint->aspectRatio();
        })->blur(1)->stream('jpg',70);
        \Storage::disk('s3')->put($path, (string) $thumbnail,['visibility'=>'public']);
        $response['tiny_photo'] = \Storage::url($path);
        $meta = getimagesize($request->input('image'));
        $response['meta']['width'] = $meta[0];
        $response['meta']['height'] = $meta[1];
        $response['meta']['mime'] = $meta['mime'];
        $response['meta']['size'] = null;
        $response['meta']['tiny_photo'] = $response['tiny_photo'];
        if(!$response){
            throw new \Exception("Could not save image " . $imageName . " at " . $path);
        }
        $this->model = $response;
        return $this->sendResponse();
    }

    public function createFilters(Request $request)
    {
        $user = $request->user();
        $id = $request->input('uuid');
        $this->model = $this->model->where('id',$id)->get();
        \App\Filter\PublicReviewProduct::addModel($this->model);
        $this->model->update(['updated_at'=>$this->now]);
        return $this->sendResponse();
    }


}
