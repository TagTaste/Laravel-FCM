<?php

namespace App\Http\Controllers;

use App\CompanyCatalogue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyCatalogueController extends Controller
{
	/**
	 * Variable to model
	 *
	 * @var company_catalogue
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(CompanyCatalogue $model)
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
		$company_catalogues = $this->model->paginate();

		return view('company_catalogues.index', compact('company_catalogues'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('company_catalogues.create');
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

		return redirect()->route('company_catalogues.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$company_catalogue = $this->model->findOrFail($id);
		
		return view('company_catalogues.show', compact('company_catalogue'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$company_catalogue = $this->model->findOrFail($id);
		
		return view('company_catalogues.edit', compact('company_catalogue'));
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

		$company_catalogue = $this->model->findOrFail($id);		
		$company_catalogue->update($inputs);

		return redirect()->route('company_catalogues.index')->with('message', 'Item updated successfully.');
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

		return redirect()->route('company_catalogues.index')->with('message', 'Item deleted successfully.');
	}
}