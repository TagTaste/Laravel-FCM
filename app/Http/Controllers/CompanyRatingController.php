<?php

namespace App\Http\Controllers;

use App\CompanyRating;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyRatingController extends Controller
{
	/**
	 * Variable to model
	 *
	 * @var company_rating
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(CompanyRating $model)
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
		$company_ratings = $this->model->paginate();

		return view('company_ratings.index', compact('company_ratings'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('company_ratings.create');
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

		return redirect()->route('company_ratings.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$company_rating = $this->model->findOrFail($id);
		
		return view('company_ratings.show', compact('company_rating'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$company_rating = $this->model->findOrFail($id);
		
		return view('company_ratings.edit', compact('company_rating'));
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

		$company_rating = $this->model->findOrFail($id);		
		$company_rating->update($inputs);

		return redirect()->route('company_ratings.index')->with('message', 'Item updated successfully.');
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

		return redirect()->route('company_ratings.index')->with('message', 'Item deleted successfully.');
	}
}