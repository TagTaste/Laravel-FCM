<?php

namespace App\Http\Controllers;

use App\CollaborationField;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CollaborationFieldController extends Controller
{
	/**
	 * Variable to model
	 *
	 * @var collaboration_field
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(CollaborationField $model)
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
		$collaboration_fields = $this->model->paginate();

		return view('collaboration_fields.index', compact('collaboration_fields'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('collaboration_fields.create');
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

		return redirect()->route('collaboration_fields.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$collaboration_field = $this->model->findOrFail($id);
		
		return view('collaboration_fields.show', compact('collaboration_field'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$collaboration_field = $this->model->findOrFail($id);
		
		return view('collaboration_fields.edit', compact('collaboration_field'));
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

		$collaboration_field = $this->model->findOrFail($id);		
		$collaboration_field->update($inputs);

		return redirect()->route('collaboration_fields.index')->with('message', 'Item updated successfully.');
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

		return redirect()->route('collaboration_fields.index')->with('message', 'Item deleted successfully.');
	}
}