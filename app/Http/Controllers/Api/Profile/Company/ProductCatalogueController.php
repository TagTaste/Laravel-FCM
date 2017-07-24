<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\ProductCatalogue;
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
	public function index($profileId, $companyId)
	{
		$this->model = $this->model->where('company_id',$companyId)->paginate();
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
	public function store(Request $request)
	{
		$inputs = $request->all();
		if(!$request->hasFile("file")){
		    return $this->sendError("File not uploaded.");
        }
        
		$this->model->create($inputs);

		return redirect()->route('product_catalogues.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->model->destroy($id);

		return redirect()->route('product_catalogues.index')->with('message', 'Item deleted successfully.');
	}
}