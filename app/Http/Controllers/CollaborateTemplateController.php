<?php

namespace App\Http\Controllers;

use App\CollaborateTemplate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CollaborateTemplateController extends Controller
{
	/**
	 * Variable to model
	 *
	 * @var collaborate_template
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(CollaborateTemplate $model)
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
		$collaborate_templates = $this->model->paginate();

		return view('collaborate_templates.index', compact('collaborate_templates'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('collaborate_templates.create');
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

		return redirect()->route('collaborate_templates.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$collaborate_template = $this->model->findOrFail($id);
		
		return view('collaborate_templates.show', compact('collaborate_template'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$collaborate_template = $this->model->findOrFail($id);
		
		return view('collaborate_templates.edit', compact('collaborate_template'));
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

		$collaborate_template = $this->model->findOrFail($id);		
		$collaborate_template->update($inputs);

		return redirect()->route('collaborate_templates.index')->with('message', 'Item updated successfully.');
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

		return redirect()->route('collaborate_templates.index')->with('message', 'Item deleted successfully.');
	}
}