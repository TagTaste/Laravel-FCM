<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\Company;
use App\ProductCatalogue;
use App\Strategies\Paginator;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class ProductCatalogueController extends Controller
{
	/**
	 * Variable to model
	 *
	 * @var product_catalogue
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(ProductCatalogue $model)
	{
		$this->model = $model;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request, $profileId, $companyId)
	{
        $page = $request->input('page');
        list($skip,$take) = Paginator::paginate($page,10);
		$this->model['data'] = $this->model->where('company_id',$companyId)->orderByRaw('category asc, product asc')->skip($skip)->take($take)->get();

		if($this->model->count() == 0){
		    return $this->sendError("Data not found.");
        }
        $this->model['totalPage'] = ceil(ProductCatalogue::where('company_id',$companyId)->count()/10);
        $this->model['count'] = ProductCatalogue::where('company_id',$companyId)->count();
        return $this->sendResponse();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//download excel
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request, $profileId, $companyId)
    {
        if($request->has("remove")&&$request->input("remove")==1)
        {
            $this->model = ProductCatalogue::where('company_id',$companyId)->delete();
            return $this->sendResponse();
        }
        //we have the file
        $filename = str_random(32) . ".xlsx";
        $path = "images/c/" . $companyId;
		$file = $request->file('file')->storeAs($path,$filename,['visibility'=>'public']);
		//$fullpath = env("STORAGE_PATH",storage_path('app/')) . $path . "/" . $filename;
		//$fullpath = \Storage::url($file);

        //load the file
        $data = [];
        try {
            $fullpath = $request->file->store('temp', 'local');
            \Excel::load("storage/app/" . $fullpath, function($reader) use (&$data){
                $data = $reader->toArray();
            })->get();
            if(empty($data)){
                return $this->sendError("Empty file uploaded.");
            }
            \Storage::disk('local')->delete($file);
        } catch (\Exception $e){
		    \Log::info($e->getMessage());
            return $this->sendError($e->getMessage());
    
        }
        $product = [];
        $temp = [];
        foreach($data as $sheet){
            foreach($sheet as $element){
                if(isset($element['product_name'])) {
                    $product['product'] = $element['product_name'];
                    $product['category'] = isset($element['category']) ? $element['category'] : null;
                    $product['company_id'] = $companyId;
                    $product['brand'] = isset($element['brand']) ? $element['brand'] : null;
                    $product['measurement_unit'] = isset($element['measurement_unit']) ? $element['measurement_unit'] : null;
                    $product['barcode'] = isset($element['barcode']) ? $element['barcode'] : null;
                    $product['size'] = isset($element['size']) ? $element['size'] : null;
                    $product['certified'] = isset($element['fssai_compliant']) ? (strtolower($element['fssai_compliant'])=='yes'? 1 : 0) : null;
                    $product['delivery_cities'] = isset($element['delivery_cities']) ? $element['delivery_cities'] : null;
                    $product['price'] = isset($element['price']) ? $element['price'] : null;
                    $product['moq'] = isset($element['moq']) ? $element['moq'] : null;
                    $product['type'] = isset($element['type']) ? $element['type'] : null;
                    $product['about'] = isset($element['product_description']) ? $element['product_description'] : null;
                    $product['shelf_life'] = isset($element['shelf_life']) ? $element['shelf_life'] : null;
                    $temp[] = $product;
                }
            }
        }

        if(count($temp)==0)
        {
            return $this->sendError("Product Column is compulsory in xls sheet.");
        }
//        create new catalogue products
        try{
            $productIds = ProductCatalogue::where('company_id',$companyId)->get()->pluck('id');
            $this->model['data'] = ProductCatalogue::insert($temp);
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            return $this->sendError("Please upload correct xls file.");
        }
        ProductCatalogue::whereIn('id',$productIds)->delete();
        $this->model['product_catalogue_count'] = ProductCatalogue::where('company_id',$companyId)->count();
        $this->model['product_catalogue_category_count'] = ProductCatalogue::where('company_id',$companyId)->whereNotNull('category')->groupBy('category')->count();
		return $this->sendResponse();
	}
    
    public function update(Request $request, $profileId, $companyId, $id)
    {
        
        $product = ProductCatalogue::where('company_id',$companyId)->where('id',$id)->first();
        if(!$product){
            return $this->sendError("Could not find product.");
        }
        
        $this->model =$product->update($request->except(['company_id']));
        return $this->sendResponse();
        
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->model = $this->model->destroy($id);
		return $this->sendResponse();
	}
}
