<?php

namespace App\Http\Controllers;

use App\CollaborateCategoryPivot;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CollaborateCategoryPivotController extends Controller
{
	/**
	 * Variable to model
	 *
	 * @var collaborate_category_pivot
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(CollaborateCategoryPivot $model)
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
		$collaborate_category_pivots = $this->model->paginate();

		return view('collaborate_category_pivots.index', compact('collaborate_category_pivots'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('collaborate_category_pivots.create');
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

		return redirect()->route('collaborate_category_pivots.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$collaborate_category_pivot = $this->model->findOrFail($id);
		
		return view('collaborate_category_pivots.show', compact('collaborate_category_pivot'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$collaborate_category_pivot = $this->model->findOrFail($id);
		
		return view('collaborate_category_pivots.edit', compact('collaborate_category_pivot'));
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

		$collaborate_category_pivot = $this->model->findOrFail($id);		
		$collaborate_category_pivot->update($inputs);

		return redirect()->route('collaborate_category_pivots.index')->with('message', 'Item updated successfully.');
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

		return redirect()->route('collaborate_category_pivots.index')->with('message', 'Item deleted successfully.');
	}
}