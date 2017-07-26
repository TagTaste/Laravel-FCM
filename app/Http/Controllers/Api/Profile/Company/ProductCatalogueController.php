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
        list($skip,$take) = Paginator::paginate($page);
        
		$this->model = $this->model->where('company_id',$companyId)->skip($skip)->take($take)->get();
		
		if($this->model->count() == 0){
		    return $this->sendResponse();
        }
        
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
        $company = Company::find($companyId);
        if(!$company){
            return $this->sendError("Company not found.");
        }
    
        if(!$request->hasFile("file")){
            return $this->sendError("File not uploaded.");
        }
    
        $user = \App\Profile\User::find($request->user()->id);
        $isPartOfCompany = $user->isPartOfCompany($companyId);
    
        if(!$isPartOfCompany){
            $this->sendError("This company does not belong to user.");
        }
        
        //we have the file
        $filename = str_random(32) . ".xlsx";
        $path = "images/c/" . $companyId;
		$file = $request->file('file')->storeAs($path,$filename);
		$fullpath = env("STORAGE_PATH",storage_path('app/')) . $path . "/" . $filename;

        //load the file
        $data = [];
        try {
            \Excel::load($fullpath, function($reader) use (&$data){
                $data = $reader->toArray();
            })->get();
            if(empty($data)){
                return $this->sendError("Empty file uploaded.");
            }
        } catch (\Exception $e){
		    \Log::info($e->getMessage());
            return $this->sendError($e->getMessage());
    
        }
        
        foreach($data as &$element){
            $element['company_id'] = $companyId;
        }
        unset($element);
    
        //delete all previous catalogue products
        ProductCatalogue::where('company_id',$companyId)->delete();
        
        //create new catalogue products
        $this->model = ProductCatalogue::insert($data);
		return $this->sendResponse();
	}
    
    public function update(Request $request, $profileId, $companyId, $id)
    {
        $company = Company::find($companyId);
        
        if(!$company){
            return $this->sendError("Company does not exist");
        }
        
        $user = \App\Profile\User::find($request->user()->id);
        $isPartOfCompany = $user->isPartOfCompany($companyId);
    
        if(!$isPartOfCompany){
            $this->sendError("This company does not belong to user.");
        }
        
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