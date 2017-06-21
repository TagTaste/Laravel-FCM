<?php namespace App\Http\Controllers\Api\Profile\Company;

use App\Company\Product;
use App\Http\Controllers\Api\Controller;
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
        $this->model = Product::where('company_id', $companyId)->orderBy('id', 'desc');

        if($request->has('categories')){
            $categories = $request->input('categories');
            $this->model = $this->model->whereHas('categories',function($query) use ($categories){
                $query->whereIn('category_id',$categories);
            });
        }
        
        $this->model = $this->model->paginate(10);
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
        
        $company = $request->user()->companies()->where('id', $companyId)->first();
        if (!$company) {
            throw new \Exception("This company does not belong to user.");
        }
        
        $product = new Product();
        $product->name = $request->input("name");
        $product->price = $request->input("price");
        $product->image = "http://placehold.it/10x10";
        if ($request->hasFile('image')) {
            $filename = $request->user()->id . str_random(25) . ".jpeg";
            $request->image->storeAs('product_images', $filename);
            $product->image = $filename;
        }
        
        $product->moq = $request->input("moq");
        $product->type = $request->input("type");
        $product->about = $request->input("about");
        $product->ingredients = $request->input("ingredients");
        $product->certifications = $request->input("certifications");
        $product->portion_size = $request->input("portion_size");
        $product->shelf_life = $request->input("shelf_life");
        $product->mode = $request->input("mode");
        $product->company_id = $company->id;
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
        $company = $request->user()->companies()->where('id', $companyId)->first();
        
        if (!$company) {
            throw new \Exception("This company does not belong to user.");
        }
        
        
        $product = Product::findOrFail($id);
        
        $product->name = $request->input("name");
        $product->price = $request->input("price");
        if ($request->hasFile('image')) {
            $product->image = $request->image->store('product_images');
        }
        $product->moq = $request->input("moq");
        $product->type = $request->input("type");
        $product->about = $request->input("about");
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
        $company = $request->user()->companies()->where('id', $companyId)->first();
        if (!$company) {
            throw new \Exception("This company does not belong to user.");
        }
        $this->model = $company->products()->where('id', $id)->delete();
        return $this->sendResponse();
    }
    
}
