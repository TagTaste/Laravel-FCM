<?php

namespace App\Http\Controllers\Api;

use App\CollaborateTemplate;
use Illuminate\Http\Request;

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
		$this->model = $this->model->paginate();
        return $this->sendResponse();
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
		$this->model = $this->model->create($inputs);
  
		return $this->sendResponse();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$this->model = $this->model->findOrFail($id);
        return $this->sendResponse();
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
        $this->model =	$collaborate_template->update($inputs);
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