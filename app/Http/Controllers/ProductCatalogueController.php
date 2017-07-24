<?php

namespace App\Http\Controllers;

use App\ProductCatalogue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
	public function index()
	{
		$product_catalogues = $this->model->paginate();

		return view('product_catalogues.index', compact('product_catalogues'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('product_catalogues.create');
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
		$this->model->create($inputs);

		return redirect()->route('product_catalogues.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$product_catalogue = $this->model->findOrFail($id);
		
		return view('product_catalogues.show', compact('product_catalogue'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$product_catalogue = $this->model->findOrFail($id);
		
		return view('product_catalogues.edit', compact('product_catalogue'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @param Request $request
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$inputs = $request->all();

		$product_catalogue = $this->model->findOrFail($id);		
		$product_catalogue->update($inputs);

		return redirect()->route('product_catalogues.index')->with('message', 'Item updated successfully.');
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