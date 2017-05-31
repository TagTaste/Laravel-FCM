<?php namespace App\Http\Controllers\Api\Profile\Company;

use App\Http\Requests;
use App\Company\Product;
use App\ProfileType;
use \Tagtaste\Api\SendsJsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class ProductController extends Controller {

    use SendsJsonResponse;
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request, $profileId, $companyId)
	{
		$this->model = Product::where('company_id',$companyId)->orderBy('id', 'desc')->paginate(10);

		return $this->sendResponse();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request,$profileid,$companyId)
	{
		$product->productCategory()->sync(array($request->input('categories')));

        $company = $request->user()->companies()->where('id',$companyId)->first();
        
        if(!$company){
            throw new \Exception("This company does not belong to user.");
        }
        
		$product = new Product();

		$product->name = $request->input("name");
        $product->price = $request->input("price");
        $product->image = "http://placehold.it/10x10";
        if($request->hasFile('image')){
        	$filename = $request->user()->id . str_random(25) . ".jpeg";
        	$request->image->storeAs('product_images',$filename);
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
        $this->model = $product;
		return $this->sendResponse();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($profileId, $companyId, $id)
	{
		$this->model = Product::where('id',$id)->where('company_id',$companyId)->first();

		return $this->sendResponse();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @param Request $request
	 * @return Response
	 */
	public function update(Request $request, $profileid, $companyId, $id)
	{
        $company = $request->user()->companies()->where('id',$companyId)->first();
        
        if(!$company){
            throw new \Exception("This company does not belong to user.");
        }
        
        
        $product = Product::findOrFail($id);

		$product->name = $request->input("name");
        $product->price = $request->input("price");
		if($request->hasFile('image')){
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
        $this->model = $product;
		return $this->sendResponse();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $profileId, $companyId, $id)
	{
        $company = $request->user()->companies()->where('id',$companyId)->first();
        
        if(!$company){
            throw new \Exception("This company does not belong to user.");
        }
        
        $this->model = $company->products()->where('id',$id)->delete();

		return $this->sendResponse();
	}

}
