<?php namespace App\Http\Controllers\Api\Profile\Company;

use App\Company\Product;
use App\Http\Controllers\Api\Controller;
use App\Strategies\Paginator;
use Illuminate\Http\Request;
use Tagtaste\Api\SendsJsonResponse;

class ProductController extends Controller
{
    
    use SendsJsonResponse;
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request, $profileId, $companyId)
    {
        $this->model = [];
        $products = Product::where('company_id', $companyId)->orderBy('id', 'desc');
        $page = $request->input('page');
        list($skip,$take) = Paginator::paginate($page,10);
        if($request->has('categories')){
            $categories = $request->input('categories');
            $products = $products->whereHas('categories',function($query) use ($categories){
                $query->whereIn('category_id',$categories);
            });
        }
        
        $this->model['data'] = $products->skip($skip)->take($take)->get();
        $this->model['count'] = Product::where('company_id', $companyId)->count();
        return $this->sendResponse();
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request, $profileid, $companyId)
    {
        $product = new Product();
        $product->name = $request->input("name");
        $product->price = $request->has("price") && !empty($request->input("price")) ? $request->input("price") : null;
        $product->category = $request->input("category");
        if ($request->hasFile('image')) {
            $path = $product->getProductImagePath($profileid, $companyId);
            $response = $this->saveFile($path,$request,"image_meta");
            $product->image = $response['original_photo'];
            $product->image_meta = json_encode($response,true);
        }

        $product->moq = $request->input("moq");
        $product->description = $request->input("description");
        $product->delivery_cities = $request->input("delivery_cities");
        $product->type = $request->input("type");
        $product->ingredients = $request->input("ingredients");
        $product->certifications = $request->input("certifications");
        $product->portion_size = $request->input("portion_size");
        $product->shelf_life = $request->input("shelf_life");
        $product->mode = $request->input("mode");
        $product->company_id = $companyId;
        $product->save();
        
        $categories = $request->input('categories');
        $product->categories()->sync($categories);
        $product->refresh();
        $this->model = $product;
        return $this->sendResponse();
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show(Request $request, $profileId, $companyId, $id)
    {
        $this->model = Product::where('id', $id)->where('company_id', $companyId)->get();
        return $this->sendResponse();
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param Request $request
     * @return Response
     */
    public function update(Request $request, $profileid, $companyId, $id)
    {
        $product = Product::findOrFail($id);
        
        $product->name = $request->input("name");
        $product->price = $request->has("price") && !empty($request->input("price")) ? $request->input("price") : null;
        $product->category = $request->input("category");
        if ($request->hasFile('image')) {
            $path = Product::getProductImagePath($profileid, $companyId);
            $response = $this->saveFile($path,$request,"image_meta");
            $product->image = $response['original_photo'];
            $product->image_meta = json_encode($response,true);
        }
        $product->moq = $request->input("moq");
        $product->description = $request->input("description");
        $product->delivery_cities = $request->input("delivery_cities");
        $product->type = $request->input("type");
        $product->ingredients = $request->input("ingredients");
        $product->certifications = $request->input("certifications");
        $product->portion_size = $request->input("portion_size");
        $product->shelf_life = $request->input("shelf_life");
        $product->mode = $request->input("mode");
        $product->save();
        
        $categories = $request->input('categories');
        $product->categories()->sync($categories);
        $product->refresh();
        $this->model = $product;
        return $this->sendResponse();
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy(Request $request, $profileId, $companyId, $id)
    {
        $this->model = Product::where('id', $id)->delete();
        return $this->sendResponse();
    }

    private function saveFile($path,&$request,$key)
    {
        $imageName = str_random("32") . ".jpg";
        $response['original_photo'] = \Storage::url($request->file($key)->storeAs($path."/original",$imageName,['visibility'=>'public']));
        //create a tiny image
        $path = $path."/tiny/" . str_random(20) . ".jpg";
        $thumbnail = \Image::make($request->file($key))->resize(50, null,function ($constraint) {
            $constraint->aspectRatio();
        })->blur(1)->stream('jpg',70);
        \Storage::disk('s3')->put($path, (string) $thumbnail,['visibility'=>'public']);
        $response['tiny_photo'] = \Storage::url($path);
        $response['meta'] = getimagesize($request->input($key));
        if(!$response){
            throw new \Exception("Could not save image " . $imageName . " at " . $path);
        }
        return $response;
    }
    
}
